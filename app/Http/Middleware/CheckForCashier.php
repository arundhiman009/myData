<?php

namespace App\Http\Middleware;

use Auth;
use Session;
use Closure;

class CheckForCashier
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
        if(Auth::check() && Auth::user()->hasRole("Cashier"))
        {
            return $next($request);
        }
        return redirect(route('login'));
    }
}
