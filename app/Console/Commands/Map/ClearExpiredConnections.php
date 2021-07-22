<?php

namespace App\Console\Commands\Map;

use App\Models\Pathwire\Connection;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearExpiredConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wirepath:connections:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Connection::where('created_at', '<', Carbon::parse('-24 hours'))->delete();
        return 0;
    }
}
