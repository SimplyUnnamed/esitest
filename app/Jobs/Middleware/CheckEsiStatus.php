<?php


namespace App\Jobs\Middleware;


use App\Jobs\EsiBase;
use App\Models\EsiStatus;

class CheckEsiStatus
{
    public function handle($job, $next)
    {
        if(is_subclass_of($job, EsiBase::class))
        {
            if(!$this->EsiIsOnline()){

                logger()->warning(
                    "esi seems to be offline"
                );
                return;
            }
        }
        $next($job);
    }

    private function EsiIsOnline(): bool
    {
        $status = cache()->remember('esi_db_status', 60, function(){
            return EsiStatus::latest()->first();
        });

        if(!$status) return true;

        if($status->created_at->lte(carbon('now')->subMinutes(30)))
            return false;

        return $status->status == 'ok';
    }

}
