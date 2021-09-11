<?php

namespace App\Http\Livewire;

use App\Models\Character;
use Livewire\Component;

class NearbySystems extends Component
{

    public $characters;

    public $character;

    public $range = 3;

    public function mount()
    {
        $this->characters = auth()->user()->characters;
        $this->character = $this->characters->first();
        $this->range = 5;
        $this->nearBy = collect([]);
    }

    public function getNearBySystemsProperty()
    {
        return $this->character->currentLocation->system->systemsWithinJumps($this->range);
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
