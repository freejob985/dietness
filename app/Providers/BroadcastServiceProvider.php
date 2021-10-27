<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(request()->hasHeader('authorization')){
            if(auth()->guard('users')->check()){
                Broadcast::routes(["middleware" => "auth:users"]);
                $user = auth()->guard('users')->user()->id;
                request()->setUserResolver(function () use ($user) {
                    return $user;
                });
            }
           
        }else{
            Broadcast::routes();
        }
        

        require base_path('routes/channels.php');
    }
}
