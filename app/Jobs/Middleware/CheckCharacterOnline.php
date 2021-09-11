<?php


namespace App\Jobs\Middleware;


use App\Jobs\AbstractedAuthCharacterJob;
use App\Jobs\EsiBase;

class CheckCharacterOnline
{
    public function handle($job, $next)
    {
        if(! is_subclass_of($job, EsiBase::class)){
            $next($job);
            return;
        }

        if(cache()->tags('online')->get($job->getCharacterId(), false)){
            $next($job);
            return;
        }

    }
}
