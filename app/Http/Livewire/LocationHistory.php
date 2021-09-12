<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LocationHistory extends Component
{

    public $characters;

    public $locations;

    public function mount()
    {
        $this->locations = \App\Models\LocationHistory::whereIn('character_id', $this->characters->pluck('character_id'))
            ->latest()->limit(10)->get();
    }

    public function getListeners(): array
    {
        $listners = [];
        foreach ($this->characters->pluck('character_id') as $character_id) {
            $listners["echo-private:location.updated.{$character_id},.LocationUpdated"] = 'getHistory';
        }
        return $listners;
    }

    public function getHistory(){
        $this->locations = \App\Models\LocationHistory::whereIn('character_id', $this->characters->pluck('character_id'))
            ->latest()->limit(10)->get();
    }


    public function render()
    {
        return view('livewire.location-history');
    }
}
