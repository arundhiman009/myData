<?php

namespace App\Http\Middleware;

use Auth;
use Session;
use Closure;

class CheckForAdminCashier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {  
        if(Auth::check() && (Auth::user()->hasRole("Cashier") || Auth::user()->hasRole("Admin")))
        {
            
            return $next($request);
        }
        return redirect(route('login'));
    }
}
