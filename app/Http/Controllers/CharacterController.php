<?php

namespace App\Http\Controllers;

use App\Jobs\Location\Character\Location;
use App\Models\Character;


class CharacterController extends Controller
{

    public function toggleTracking(Character $character){
        $character->toggleTracking();
        return back();
    }

    public function getLocation(Character $character){
        Location::dispatchSync($character->RefreshToken);
        return back();
    }

    public function queueLocation(Character $character){
        Location::dispatch($character->RefreshToken)->onQueue('locations');
        return back();
    }
}
