<?php

namespace App\Socialite\EveOnline;

use App\Image\Eve;
use App\Socialite\EveOnline\Checker\Claim\AzpChecker;
use App\Socialite\EveOnline\Checker\Claim\NameChecker;
use App\Socialite\EveOnline\Checker\Claim\OwnerChecker;
use App\Socialite\EveOnline\Checker\Claim\ScpChecker;
use App\Socialite\EveOnline\Checker\Claim\SubEveCharacterChecker;
use App\Socialite\EveOnline\Checker\Header\TypeChecker;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;
use Jose\Component\Core\JWKSet;
use Jose\Easy\Load;

class Provider extends AbstractProvider
{

    public const IDENTIFIER = 'EVE';

    protected $scopeSeparator = ' ';

    protected $authUrl = 'https://login.eveonline.com/v2/oauth/authorize';

    protected function getTokenUrl(){
        return 'https://login.eveonline.com/v2/oauth/token';
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->authUrl, $state);
    }

    protected function getUserByToken($token){
        return $this->validateJwtToken($token);
    }

    private function validateJwtToken(string $token): array
    {
        $scopes = session()->pull('scopes', []);

        $sets = $this->getJwkSets();

        $jwk_sets = JWKSet::createFromKeyData($sets);

        $jws = Load::jws($token)
            ->algs(['RS256', 'ES256', 'HS256'])
            ->exp()
            ->iss('login.eveonline.com')
            ->header('typ', new TypeChecker(['JWT'], true))
            ->claim('scp', new ScpChecker($scopes))
            ->claim('sub', new SubEveCharacterChecker())
            ->claim('azp', new AzpChecker(config('services.eveonline.client_id')))
            ->claim('name', new NameChecker())
            ->claim('owner', new OwnerChecker())
            ->keyset($jwk_sets)
            ->run();

        return $jws->claims->all();
    }

    private function getJwkSets()
    {
        $jwk_uri = $this->getJwkUri();

        $response = $this->getHttpClient()
            ->get($jwk_uri);

        return json_decode($response->getBody(), true);
    }

    private function getJwkUri()
    {
        $response = $this->getHttpClient()
            ->get('https://login.eveonline.com/.well-known/oauth-authorization-server');

        $metadata = json_decode($response->getBody());

        return $metadata->jwks_uri;
    }

    protected function mapUserToObject(array $user)
    {
        $avatar = asset('img/evewho.png');
        $character_id = strtr($user['sub'], ['CHARACTER:EVE:' => '']);

        try {
            $avatar = (new Eve('characters', 'portrait', $character_id, 128))->url(128);
        } catch (\Exception $e) {
            logger()->error($e->getMessage(), $e->getTrace());
        }

        return (new User)->setRaw($user)->map([
            'id'                   => $character_id,
            'name'                 => $user['name'],
            'nickname'             => $user['name'],
            'character_owner_hash' => $user['owner'],
            'scopes'               => is_array($user['scp']) ? $user['scp'] : [$user['scp']],
            'expires_on'           => $user['exp'],
            'avatar'               => $avatar,
        ]);
    }
}
