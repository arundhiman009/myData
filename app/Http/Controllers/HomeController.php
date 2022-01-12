<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Game;

class HomeController extends Controller
{
    public function index(Request $request){
    	$games = Game::all();
        return view('welcome',compact('games'));
    }

    public function adminApproval()
    {
        if(Auth::user()->status == 1)
        {
            return redirect()->route('dashboard');
        }
        return view('auth.verify-admin');
    }
}
