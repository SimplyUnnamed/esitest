<?php


namespace App\Jobs\Middleware;


use App\Jobs\EsiBase;

class CheckEsiRateLimit
{

    public function handle($job, $next)
    {
        if(is_subclass_of($job, EsiBase::class)){
            if($this->isEsiRateLimited($job)){
                $job->release($job::RATE_LIMIT_DURATION);
                return;
            }
        }
        $next($job);
    }

    public function isEsiRateLimited(EsiBase $job): bool
    {
        $current = cache()->get($job::RATE_LIMIT_KEY) ?: 0;

        return $current >= $job::RATE_LIMIT;
    }
}
