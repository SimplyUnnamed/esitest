<?php

namespace App\Http\Controllers;

use App\Jobs\Location\Character\Location;
use App\Models\LocationHistory;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(){
        if(auth()->check()){
            return redirect('home');
        }
        return view('home');
    }


    public function home(){

        $characters = auth()->user()->characters;

        //Get the last 10 locations for the users characters
        /*$locations = LocationHistory::whereHas('refreshToken', function($query){
            $query->where('user_id', auth()->user()->getKey());
        })->with('character')->latest()->limit(10)->get();*/

        //Im an idiot. here's an easier way
        $locations = LocationHistory::whereIn('character_id', $characters->pluck('character_id'))
                                ->latest()->limit(10)->get();;

        return view('main', compact('characters', 'locations'));
    }

    public function logout(){
        auth()->logout();

        return redirect('/');
    }
}
