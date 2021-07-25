<?php

namespace App\Console\Commands\Location;

use App\Models\RefreshToken;
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
        $tokens = RefreshToken::whereHas('user.session')
            ->get();

        $tokens->each(function ($token) {
            \App\Jobs\Location\Character\Online::dispatch($token)->onQueue('online');
        });

        return 0;
    }
}
