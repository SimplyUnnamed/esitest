<?php


namespace App\Jobs\Middleware;


use App\Exceptions\TokenVersionException;
use App\Jobs\EsiBase;
use App\Models\RefreshToken;

class CheckTokenVersion
{

    public function handle($job, $next)
    {
        if(! is_subclass_of($job, EsiBase::class)){
            next($job);
            return;
        }

        $ver = $job->getToken()->version();

        if($ver == RefreshToken::CURRENT_VERSION){
            $next($job);
        }else{
            logger()->error("Job not run due to invalid token version", [
                'job'=> get_class($job),
                'token_id'=>$job->getToken()->character_id
            ]);
            $job->failed(new TokenVersionException("Token version is not valid"));
        }
    }
}
