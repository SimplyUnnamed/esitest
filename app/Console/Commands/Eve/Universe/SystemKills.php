<?php

namespace App\Console\Commands\Eve\Universe;

use Illuminate\Console\Command;

class SystemKills extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:universe:systemkills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get most recent System Kills information from EVE';

    public function handle()
    {
        \App\Jobs\Universe\SystemKills::dispatchSync();
    }
}
