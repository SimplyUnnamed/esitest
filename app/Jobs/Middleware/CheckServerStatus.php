<?php


namespace App\Jobs\Middleware;


use App\Jobs\EsiBase;
use App\Models\ServerStatus;

class CheckServerStatus
{

    public function handle($job, $next)
    {
        if(is_subclass_of($job, EsiBase::class)){

            if(!$this->isEveOnline()){

                logger()->warning(
                    "EVE server seems to be down"
                );

                return;
            }

        }
        $next($job);
    }

    private function isEveOnline(): bool
    {
        $status = cache()->remember('eve_db_status', 60, function(){
            return ServerStatus::latest()->first();
        });

        if(! $status) return false;

        if($status->created_at->lte(carbon('now')->subMinutes(10)))
            return false;

        return true;
    }
}
