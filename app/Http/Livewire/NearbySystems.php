<?php

namespace App\Http\Livewire;

use App\Jobs\UserInterface\SetWaypoint;
use App\Models\Character;
use App\Models\Sde\System;
use App\Models\Universe\SystemKills;
use Livewire\Component;

class NearbySystems extends Component
{

    public $characters;

    public $character;

    public $character_id;

    public $range = 3;

    public $sortBy;

    public $lastStatDate;

    public function mount()
    {
        $this->characters = auth()->user()->characters;
        $this->character_id = $this->characters->first()->getKey();
        $this->character = $this->characters->first();
        $this->lastStatDate = ($sk = SystemKills::Query()->latest()->first()) ? $sk->created_at : 'no-data';
        $this->range = 5;
        $this->sortBy = 'jumps';
        $this->nearBy = collect([]);
    }

    public function LoadCharacter(){
        $this->character = Character::find($this->character_id);
    }

    public function getNearBySystemsProperty()
    {
        return is_null($this->character->currentLocation) ?
            collect([])
            : $this->character->currentLocation->system->systemsWithinJumps($this->range+1)
            ->sortByDesc($this->sortBy);
    }



    public function setdestination($system_id)
    {
        SetWaypoint::dispatch($this->character->RefreshToken, $system_id)->onQueue('waypoint');
    }



    public function getListeners(): array
    {
        $listners = [];
        foreach ($this->characters->pluck('character_id') as $character_id) {
            $listners["echo-private:location.updated.{$character_id},.LocationUpdated"] = '$refresh';
            $listners["historyCleared"] = '$refresh';
        }
        return $listners;
    }

    public function render()
    {
        return view('livewire.nearby-systems');
    }
}
