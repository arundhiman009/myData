<?php

namespace App\Http\Middleware;

use Auth;
use Session;
use Closure;

class AdminApproval
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
      
        if(Auth::check() && auth::user()->status == 1)
        {
            return $next($request);
        }

      return redirect()->route('admin.verify');
    }
}
