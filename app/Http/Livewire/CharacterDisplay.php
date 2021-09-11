<?php

namespace App\Http\Livewire;

use App\Events\Location\OnlineStatusUpdate;
use App\Models\Character;
use Livewire\Component;

class CharacterDisplay extends Component
{

    public Character $character;

    public $eventsReceived = 0;

    public $online = false;

    public function getListeners(): array
    {
        return [
            "echo-private:online.status.{$this->character->character_id},.OnlineStatusUpdate"=> '$refresh'
        ];
    }


    public function getOnlineProperty()
    {
        return $this->character->online;
    }

    public function getTrackingProperty()
    {
        return $this->character->tracking;
    }

    public function getCurrentSystemProperty()
    {
        return $this->character->currentLocation->system;
    }


    public function toggle_tracking(){
        $this->character->toggleTracking();
    }

    public function render()
    {
        return view('livewire.character-display');
    }
}
