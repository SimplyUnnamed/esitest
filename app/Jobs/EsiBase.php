<?php


namespace App\Jobs;


use App\Exceptions\PermanentInvalidTokenException;
use App\Exceptions\TemporaryEsiOutageException;
use App\Exceptions\UnavailableEveServerException;
use App\Exceptions\UnavailableEveServersException;
use App\Jobs\Middleware\CheckEsiRateLimit;
use App\Jobs\Middleware\CheckEsiStatus;
use App\Jobs\Middleware\CheckServerStatus;
use App\Models\RefreshToken;
use Exception;
use Illuminate\Support\Facades\Redis;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Containers\EsiResponse;
use Seat\Eseye\Exceptions\RequestFailedException;
use Throwable;

abstract class EsiBase extends AbstractJob
{
    const RATE_LIMIT = 80;

    const RATE_LIMIT_DURATION = 300;

    const RATE_LIMIT_KEY = 'esiratelimit';

    const PERMANENT_INVALID_TOKEN_MESSAGES = [
        'invalid_token: The refresh token is expired.',
        'invalid_token: The refresh token does not match the client specified.',
        'invalid_grant: Invalid refresh token. Character grant missing/expired.',
        'invalid_grant: Invalid refresh token. Unable to migrate grant.',
        'invalid_grant: Invalid refresh token. Token missing/expired.',
    ];

    /**
     * @var string By default, queue all ESI jobs on public queue.
     */
    public $queue = 'public';

    /**
     * @var int By default, retry all ESI jobs 3 times.
     */
    public $tries = 1;

    /**
     * The HTTP method used for the API Call.
     *
     * Eg: GET, POST, PUT, DELETE
     *
     * @var string
     */
    protected $method = '';

    /**
     * The ESI endpoint to call.
     *
     * Eg: /characters/{character_id}/
     *
     * @var string
     */
    protected $endpoint = '';

    /**
     * The endpoint version to use.
     *
     * Eg: v1, v4
     *
     * @var int
     */
    protected $version = '';

    /**
     * The SSO scope required to make the call.
     *
     * @var string
     */
    protected $scope = 'public';

    /**
     * The page to retrieve.
     *
     * Jobs that expect paged responses should have
     * this value set.
     *
     * @var int
     */
    protected $page = null;

    /**
     * The body to send along with the request.
     *
     * @var array
     */
    protected $request_body = [];

    /**
     * Any query string parameters that should be sent
     * with the request.
     *
     * @var array
     */
    protected $query_string = [];

    /**
     * @var \Seat\Eveapi\Models\RefreshToken
     */
    protected $token;

    /**
     * @var \Seat\Eseye\Eseye|null
     */
    protected $client;

    public function tags(): array
    {
        $tags = parent::tags();
        if (is_null($this->token))
            $tags[] = 'public';

        return $tags;
    }

    public function middleware()
    {
        return [
            //check ESI status
            //new CheckEsiStatus,
            //Check Rate Limiting
            //new CheckEsiRateLimit,
            //Check server status
            //new CheckServerStatus,
        ];
    }


    public function getRateLimitKeyTtl()
    {
        return Redis::ttl('pathwire:' . self::RATE_LIMIT_KEY);
    }

    public function getRoles()
    {
        return $this->roles ?: [];
    }

    public function getToken(): ?RefreshToken
    {
        return $this->token;
    }

    public function getScope(): string
    {
        return $this->scope ?: '';
    }

    public function retryAfter()
    {
        return now()->addSeconds($this->attempts() * 300);
    }

    public function failed(Throwable $exception)
    {
        parent::failed($exception);

        if ($exception instanceof PermanentInvalidTokenException) {
            $this->token->delete();
        }
        if ($exception instanceof UnavailableEveServerException) {
            cache()->remember('eve_db_status', 60, function () {
                return null;
            });
        }
    }

    public function incrementEsiRateLimit(int $amount = 1)
    {
        if ($this->getRateLimitKeyTtl() > 3) {
            cache()->increment(self::RATE_LIMIT_KEY, $amount);
        } else {
            cache()->set(self::RATE_LIMIT_KEY, $amount, carbon('now')->addSeconds(self::RATE_LIMIT_DURATION));
        }

    }

    public function retrieve(array $path_values = []): EsiResponse
    {
        $this->validateCall();

        $client = $this->eseye();
        $client->setVersion($this->version);
        $client->setBody($this->request_body);
        $client->setQueryString($this->query_string);

        // Configure the page to get
        if (! is_null($this->page))
            $client->page($this->page);


        try{
            $result = $client->invoke($this->method, $this->endpoint, $path_values);

            $this->updateRefreshToken();
        }catch(RequestFailedException $exception){
            dd($exception);
            $this->handleEsiFailedCall($exception);
        }

        //if($result->isCacheLoad())
            //return $result;


        return $result;
    }

    public function validateCall()
    {
        if (!in_array($this->method, ['get', 'post', 'put', 'patch', 'delete']))
            throw new Exception('Invalid HTTP method used');

        if (trim($this->endpoint) === '')
            throw new Exception('Empty endpoint used');

        // Enfore a version specification unless this is a 'meta' call.
        if (trim($this->version) === '' && !(in_array('meta', $this->tags())))
            throw new Exception('Version is empty');
    }

    private function eseye()
    {
        $this->client = app('esi-client');

        if (is_null($this->token))
            return $this->client = $this->client->get();


        $this->token = $this->token->fresh();

        return $this->client = $this->client->get(new EsiAuthentication([
            'refresh_token' => $this->token->refresh_token,
            'access_token' => $this->token->token,
            'token_expires' => $this->token->expires_on,
            'scopes' => $this->token->scopes,
        ]));
    }

    public function updateRefreshToken()
    {
        tap($this->token, function($token){
            if(is_null($this->client) || is_null($token))
                return;

            if(!$this->client->isAuthenticated())
                return;

            $last_auth = $this->client->getAuthentication();


            if(! empty($last_auth->refresh_token))
                $token->refresh_token = $last_auth->refresh_token;

            $token->token = $last_auth->access_token ?? '-';
            $token->expires_on = $last_auth->token_expires;

            $token->save();
        });
    }

    public function handleEsiFailedCall(RequestFailedException $exception)
    {
        $this->incrementEsiRateLimit();

        $response = $exception->getEsiResponse();

        $this->updateRefreshToken();

        if($response->getErrorCode() == 403 && $response->error() == 'token expiry is too far in the future'){
            if($this->token) {
                $this->token->expires_on = carbon()->subMinutes(10);
                $this->token->save();
            }

            throw new TemporaryEsiOutageException($response->error(), $response->getErrorCode());
        }

        if($response->getErrorCode() == 400 && in_array($response->error(), self::PERMANENT_INVALID_TOKEN_MESSAGES))
            throw new PermanentInvalidTokenException($response->error(), $response->getErrorCode());

                if (($response->getErrorCode() == 503 && $response->error() == 'The datasource tranquility is temporarily unavailable') ||
            ($response->getErrorCode() == 504 && $response->error() == 'Timeout contacting tranquility'))
            throw new UnavailableEveServersException($response->error(), $response->getErrorCode());

        if ($response->getErrorCode() >= 500)
            throw new TemporaryEsiOutageException($response->error(), $response->getErrorCode());

        // Rethrow the exception
        throw $exception;
    }


}
