<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class AdminLocal
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
          if(!Session::has('admin_locale'))
        {
            Session::put('admin_locale', 'ar');
        }

        app()->setLocale(Session::get('admin_locale'));

        return $next($request);
    }
}
