<?php

namespace App\Jobs\Universe;

use App\Events\KillStatsUpdated;
use App\Jobs\EsiBase;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SystemKills extends EsiBase implements ShouldBeUnique
{

    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/universe/system_kills/';

    /**
     * @var string
     */
    protected $version = 'v2';


    /**
     * @var array
     */
    protected $tags = ['public', 'meta'];


    public $queue = 'universe';


    public function handle()
    {
        $kills = $this->retrieve();

        if(!$kills->isCachedLoad()){
            collect($kills)->each(function($system){
                \App\Models\Universe\SystemKills::create([
                    'system_id' => $system->system_id,
                    'npc_kills' => $system->npc_kills,
                    'pod_kills' => $system->pod_kills,
                    'ship_kills'=> $system->ship_kills
                ]);
            });
            event(new KillStatsUpdated());
        }else{
            $count = \Cache::get(self::class, 0);
            if($count < 10){
                \Cache::add(self::class, $count+1, 300);
                static::delay(60);
            }
        }
    }
}
