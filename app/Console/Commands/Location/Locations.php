<?php

namespace App\Console\Location\Commands;

use App\Jobs\Location\Character\Location;
use App\Models\RefreshToken;
use Illuminate\Console\Command;


class Locations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:locations:location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the locations of tracking online and tracking characters.';

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
        //Get Tokens with valid user sessions
        $tokens = RefreshToken::whereHas('user.session')
            //and where characters are being tracked
            ->whereHas('character',function($character){
                $character->tracking();
            })->get();


        //dispatch the jobs to find them
        $tokens->each(function($token){
            Location::dispatch($token)->onQueue('locations');
        });

        return 0;
    }
}
