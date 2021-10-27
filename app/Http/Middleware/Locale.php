<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class Locale
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
          if(!Session::has('home_locale'))
        {
            Session::put('home_locale', 'ar');
        }

        app()->setLocale(Session::get('home_locale'));

        return $next($request);
    }
}
