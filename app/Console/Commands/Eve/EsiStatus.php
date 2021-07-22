<?php


namespace App\Console\Commands\Eve;

use App\Jobs\Status\Esi;
use Illuminate\Console\Command;

class EsiStatus extends Command
{

    protected $signature = 'eve:esi:status';

    protected $description = 'Schedule job to get the esi status';

    public function handle(){
        Esi::dispatch()->onQueue('high');
    }
}
