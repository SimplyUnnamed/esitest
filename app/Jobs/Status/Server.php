<?php


namespace App\Jobs\Status;


use App\Jobs\EsiBase;
use App\Models\ServerStatus;

class Server extends EsiBase
{

     /**
     * @var string
     */
    protected $method = 'get';

    /**
     * @var string
     */
    protected $endpoint = '/status/';

    /**
     * @var string
     */
    public $queue = 'high';

    /**
     * @var int
     */
    protected $version = 'v1';

    /**
     * @var array
     */
    protected $tags = ['public', 'meta'];

    /**
     * @return array
     */
    public function middleware()
    {
        return [];
    }

    public function handle()
    {
        $status = $this->retrieve();

        if($status->isCacheLoad()) return;

        $lastest_status = ServerStatus::latest()->first();

        if(!$lastest_status || $lastest_status->created_at->addSeconds(30)->lt(carbon())){
            ServerStatus::create([
                'start_time'    => carbon($status->start_time),
                'players'       => $status->platers,
                'server_version'=> $status->server_version,
                'vip'           => property_exists($status, 'vip') ? $status->vip : false
            ]);
        }
    }
}
