<?php

namespace App\Console\Commands\Map;

use App\Models\pathwire\System;
use Illuminate\Console\Command;

class ClearExpiredSystems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wirepath:systems:clear-expired';

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
        System::where('created_at', '<', Carbon::parse('-24 hours'))->delete();
        return 0;
    }
}
