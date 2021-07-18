<?php

namespace App\Http\Controllers;

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

        return view('main', compact('characters'));
    }

    public function logout(){
        auth()->logout();

        return redirect('/');
    }
}
