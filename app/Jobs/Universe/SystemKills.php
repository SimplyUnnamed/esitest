<?php

namespace App\Jobs\Universe;

use App\Jobs\EsiBase;

class SystemKills extends EsiBase
{

    protected $endpoint = '/universe/system_kills/';

    protected $method = 'get';

    protected $version = 'v2';

    protected $tags = ['public', 'universe'];



    public function handle()
    {
        $stats = $this->retrieve();
        if( !$stats->isCachedLoad()){
            foreach(json_decode($stats->raw) as $stat)
            {
                \App\Models\Universe\SystemKills::Create([
                    'system_id' => $stat->system_id,
                    'npc_kills' => $stat->npc_kills,
                    'pod_kills' => $stat->pod_kills,
                    'ship_kills'=> $stat->ship_kills
                ]);
            }
        }
    }
}
