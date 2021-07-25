<?php


namespace App\Jobs\Middleware;


use App\Jobs\EsiBase;

class CheckTokenScope
{

    public function handle($job, $next)
    {
        if(! is_subclass_of($job, EsiBase::class)){
            $next($job);
            return;
        }

        if($job->getScope() == '' || in_array($job->getScope(), $job->getToken()->scopes)){
            $next($job);
            return;
        }

        logger()->warning("Job without the required scopes has been queued", [
            'Job' => get_class($job),
            'Required Scopes' => $job->getScope(),
            'Token scopes' => $job->getToken()->scopes,
            'Token owner'   => $job->getToken()->character_id,
        ]);

    }

}
