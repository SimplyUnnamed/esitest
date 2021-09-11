<?php

namespace App\Jobs\Universe;

use App\Jobs\EsiBase;

class SystemJumps extends EsiBase
{
    /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/universe/system_jumps/';

    /**
     * @var string
     */
    protected $version = 'v1';


    /**
     * @var array
     */
    protected $tags = ['public', 'meta'];

    public function handle()
    {
        $jumps = $this->retrieve();

        if(!$jumps->isCachedLoad()){
            collect($jumps)->each(function($system){
                \App\Models\Universe\SystemJumps::create([
                    'system_id' => $system->system_id,
                    'ship_jumps' => $system->ship_jumps
                ]);
            });
        }
    }
}
