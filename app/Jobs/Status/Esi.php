<?php


namespace App\Jobs\Status;


use App\Jobs\EsiBase;
use App\Models\EsiStatus;

class Esi extends EsiBase
{
    protected $method = 'get';

    protected $endpoint = '/ping';

    public $queue = 'high';

    protected $tags = ['public', 'meta'];


    public function middleware(){
        return [];
    }

    public function handle()
    {
        $start = microtime(true);
        try {
            $status = $this->retrieve()->raw;
        } catch (\Exception $exception)
        {
            $status = 'Request failed: ' .$exception->getMessage();
        }
        $end = microtime(true) - $start;

        EsiStatus::create([
            'status'        => $status,
            'request_time'  => $end,
        ]);
    }
}
