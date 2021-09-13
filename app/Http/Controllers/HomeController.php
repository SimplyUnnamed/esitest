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

        return view('main', compact('characters'));
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
