<?php

namespace App\Console\Commands\Location;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Console\Command;

class Online extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:location:online';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues for the online status of characters';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $users = User::whereHas('session')->with('refresh_tokens')->get();

        $users->each(function($user){
            $user->refresh_tokens->each(function($token) use ($user){
                \App\Jobs\Location\Character\Online::dispatch($token, $user)->onQueue('locations');
            });
        });
        /*$tokens = RefreshToken::whereHas('user.session')
            ->get();

        $tokens->each(function ($token) {

            \App\Jobs\Location\Character\Online::dispatch($token)->onQueue('online');
        });*/

        return 0;
    }
}
