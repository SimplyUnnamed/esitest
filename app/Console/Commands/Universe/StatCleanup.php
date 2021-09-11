<?php

namespace App\Console\Commands\Universe;

use App\Jobs\Universe\SystemJumps;
use App\Jobs\Universe\SystemKills;
use Illuminate\Console\Command;

class StatCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:universe:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues for the online Stats of the universe';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SystemKills::dispatch();
        SystemJumps::dispatch();

        return 0;
    }
}
