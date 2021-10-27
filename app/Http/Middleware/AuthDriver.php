<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthDriver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->guard('driver_web')->check()){
            return redirect(route('driver_login'));
        }
        return $next($request);
    }
}
