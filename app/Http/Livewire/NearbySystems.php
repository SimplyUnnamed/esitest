<?php

namespace App\Http\Livewire;

use App\Models\Character;
use Livewire\Component;

class NearbySystems extends Component
{

    public $characters;

    public $character;

    public $range = 3;

    public $sortBy;

    public function mount()
    {
        $this->characters = auth()->user()->characters;
        $this->character = $this->characters->first();
        $this->range = 5;
        $this->sortBy = 'jumps';
        $this->nearBy = collect([]);
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
        //TODO: Implement Set Waypoint Job
    }

    public function getListeners(): array
    {
        $listners = [];
        foreach ($this->characters->pluck('character_id') as $character_id) {
            $listners["echo-private:location.updated.{$character_id},.LocationUpdated"] = '$refresh';
        }
        return $listners;
    }

    public function render()
    {
        return view('livewire.nearby-systems');
    }
}
