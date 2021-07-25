<?php

namespace App\Http\Controllers;

use App\Jobs\Location\Character\Location;
use App\Models\Character;
use App\Models\LocationHistory;
use App\Models\Pathwire\Connection;
use App\Models\Pathwire\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index(){
        if(auth()->check()){
            return redirect('home');
        }
        return view('home');
    }


    public function home(){

        $characters = Character::belongsToUser()
            ->with([
                'currentLocation.system',
                'currentLocation.station',
            ])->get();

        //Get the last 10 locations for the users characters
        /*$locations = LocationHistory::whereHas('refreshToken', function($query){
            $query->where('user_id', auth()->user()->getKey());
        })->with('character')->latest()->limit(10)->get();*/

        //Im an idiot. here's an easier way
        $locations = LocationHistory::whereIn('character_id', $characters->pluck('character_id'))
                                ->latest()->limit(10)->get();

        $systems = System::with('characters', 'system', 'characters')->get();

        $connections = Connection::with('fromSystem.system', 'toSystem.system', 'travel.character')->get();

        return view('main', compact('characters', 'locations', 'systems', 'connections'));
    }

    public function toggleDebug(){
        session()->put('debug_enabled', !session()->get('debug_enabled', false));
        return back();
    }

    public function logout(){
        auth()->logout();

        return redirect('/');
    }
}
