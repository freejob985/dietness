<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class language
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
        $locale = ($request->hasHeader('Accept-Language')) ? $request->header('Accept-Language') : 'ar';
        if(!$request->hasHeader('Accept-Language')){
            $request->headers->set('Accept-Language', 'ar');
        }
        if(!in_array($locale, ['en','ar'])){
            $locale = 'ar';
        }
        App::setLocale($locale);
        return $next($request);
    }
}
