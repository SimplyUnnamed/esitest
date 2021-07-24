<?php

namespace App\Console\Commands;

use App\Models\Pathwire\System;
use Illuminate\Console\Command;

class ClearMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the Map';

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
        System::all()->each(function($item){
            $item->delete();
        });
        return 0;
    }
}
