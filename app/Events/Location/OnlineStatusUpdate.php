<?php

namespace App\Events\Location;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnlineStatusUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var
     */
    public $character_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($character_id)
    {
        $this->character_id  = $character_id;
    }


    public function broadcastAs(){
        return 'OnlineStatusUpdate';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('online.status.'.$this->character_id);
    }
}
