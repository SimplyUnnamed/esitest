<?php


namespace App\Console\Commands\Eve;

use App\Jobs\Status\Server;
use Illuminate\Console\Command;

class ServerStatus extends Command
{

     protected $signature = 'eve:server:status';

    protected $description = 'Schedule job to get the server status';

    public function handle(){
        Server::dispatch()->onQueue('high');
    }
}
