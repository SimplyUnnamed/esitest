<?php

namespace App\Http\Controllers;

use App\Jobs\Location\Character\Location;
use App\Jobs\Location\Character\Online;
use App\Models\Character;


class CharacterController extends Controller
{

    /**
     * Toggles the tracking of a character
     * @param Character $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleTracking(Character $character){
        $character->toggleTracking();
        return back();
    }

    public function getOnline(Character $character){
        Online::dispatchNow($character->RefreshToken);
        //Online::dispatchSync($character->RefreshToken);
        return back();
    }

    /**
     * Debug function
     * Calls the Location job for a character
     * @param Character $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLocation(Character $character){
        Location::dispatchSync($character->RefreshToken);
        return back();
    }

    /**
     * Debug function
     * Queues the location job for a character.
     * @param Character $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function queueLocation(Character $character){
        Location::dispatch($character->RefreshToken)->onQueue('locations');
        return back();
    }
}
