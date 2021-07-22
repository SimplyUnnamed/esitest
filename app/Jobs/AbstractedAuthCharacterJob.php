<?php


namespace App\Jobs;


use App\Jobs\Middleware\CheckTokenScope;
use App\Jobs\Middleware\CheckTokenVersion;
use App\Models\RefreshToken;

abstract class AbstractedAuthCharacterJob extends AbstractCharacterJob
{
    public $queue = 'location';


    public function __construct(RefreshToken $token)
    {

        $this->token = $token;

        parent::__construct($token->character_id);
    }

    public function middleware()
    {
        return array_merge(parent::middleware(), [
            new CheckTokenScope,
            new CheckTokenVersion,
        ]);
    }
}
