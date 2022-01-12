<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {       
        // if(Auth::user()->hasRole("Admin"))
        // {
        //     return redirect()->route('admin.dashboard');
        // } 
        // elseif(Auth::user()->hasRole("Cashier")) 
        // {
        //     return redirect()->route('dashboard');
        // }
        // else{
        //     return redirect()->route('dashboard');
        // }
        return redirect()->route('dashboard');
        return $request->wantsJson()
                    ? response()->json(['two_factor' => false])
                    : redirect()->intended(config('fortify.home'));
    }
}