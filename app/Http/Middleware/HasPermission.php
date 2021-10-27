<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasPermission
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
        // get page from request url
        // http://localhost:8000/admin/orders/create --> orders
        $page = $request->segment(2);
        // "page" => "permission"

        $permissions = [
            "admins"=>"admins", "drivers"=>"drivers", "governorates"=>"governorates",
            "cities"=>"regions", "categories"=>"categories", "products"=>"products",
            "packages"=>"packages", "users"=>"users", "subscriptions"=>"subscriptions",
            "boxes"=>"boxes", "sliders"=>"sliders", "broadCast"=>"messages",
            "settings"=>"settings", "terms"=>"terms", "Welcome_text"=>"wellcome",
            "orders"=>"orders", "user" => "users", "plans" => "packages",
        ];

        if(isset($permissions[$page]) && ! auth()->guard("admins")->user()->hasPermission($permissions[$page])) {
            abort(404);
        }

        return $next($request);
    }
}
