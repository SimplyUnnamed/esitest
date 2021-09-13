<?php

namespace App\Http\Livewire;

use App\Jobs\Location\Character\Location;
use App\Jobs\UserInterface\SetWaypoint;
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

    public function clearHistory(){
        \App\Models\LocationHistory::whereIn('character_id', $this->characters->pluck('character_id'))->delete();
        $this->locations = collect([]);
        $this->emit('historyCleared');
    }

    public function setDestination($character_id, int $system_id){
        $character = $this->characters->where('character_id', $character_id)->first();
        SetWaypoint::dispatch($character->RefreshToken, $system_id);
    }

    public function render()
    {
        return view('livewire.location-history');
    }
}
