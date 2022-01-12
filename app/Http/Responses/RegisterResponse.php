<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Cookie;

class RegisterResponse implements RegisterResponseContract
{

    public function toResponse($request)
    {
    	$cookie = cookie('referral',null,0);

    	return redirect()->route('verification.notice');

        // echo"<pre>"; print_r($request->toArray()); die;
    }

}