<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SpawnLocationJobs implements ShouldQueuew
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $active_users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($active_sessions)
    {
        $this->activive_sessions = $active_sessions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
