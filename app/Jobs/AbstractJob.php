<?php


namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    abstract public function handle();

    public function tags(): array
    {
        if (property_exists($this, 'tags')) {
            return $this->tags;
        }
        return ['other'];
    }

    public function failed(\Throwable $excepition)
    {
        logger()->error(
            "this job failed", ['exception' => $excepition->getMessage()]
        );
    }

    public function retryUntil(){
        return now()->addSecond(10);
    }
}
