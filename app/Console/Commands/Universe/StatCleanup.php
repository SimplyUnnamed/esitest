<?php

namespace App\Console\Commands\Universe;

use App\Jobs\Universe\Cleanup;
use Illuminate\Console\Command;

class StatCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:universe:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up the stats database';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Cleanup::dispatch();

        return 0;
    }
}
