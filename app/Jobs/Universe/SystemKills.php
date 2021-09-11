<?php

namespace App\Jobs\Universe;

use App\Jobs\EsiBase;

class SystemKills extends EsiBase
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
        }
    }
}
