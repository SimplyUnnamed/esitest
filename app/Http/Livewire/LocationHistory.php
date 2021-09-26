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
            ->where('locked', true)
            ->latest()->limit(10)->get();

        $temp = \App\Models\LocationHistory::whereIn('character_id', $this->characters->pluck('character_id'))
            ->where('locked', false)
            ->latest()->limit(10 - $this->locations->count())->get();

        $this->locations = ($this->locations->concat($temp))->sortBy('created_at');
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

    public function toggleLock($id)
    {
        $system = $this->locations->where('id', $id)->first();
        $system->locked = !$system->locked;
        $system->save();
    }

    public function clearHistory(){
        $keep =  \App\Models\LocationHistory::whereIn('character_id', $this->characters->pluck('character_id'))
            ->latest()
            ->take(1)
            ->pluck('id');
        \App\Models\LocationHistory::whereIn('character_id', $this->characters->pluck('character_id'))
            ->where('locked', false)
            ->whereNotIn('id', $keep)->delete();
        //$this->locations = collect([]);
        $this->mount();
        $this->emit('historyCleared');
    }

    public function setDestination($character_id, int $system_id){
        $character = $this->characters->where('character_id', $character_id)->first();
        SetWaypoint::dispatch($character->RefreshToken, $system_id)->onQueue('waypoint');;
    }

    public function render()
    {
        return view('livewire.location-history');
    }
}
