<?php

namespace App\Jobs\Universe;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Cleanup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\Models\Universe\SystemKills::where('created_at', '<', Carbon::now()->subHours(48)->toDateTime())->delete();
        \App\Models\Universe\SystemJumps::where('created_at', '<', Carbon::now()->subHours(48)->toDateTime())->delete();
    }
}
