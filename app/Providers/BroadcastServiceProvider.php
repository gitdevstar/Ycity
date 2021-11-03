<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
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
        Broadcast::routes();

        Broadcast::channel('Online', function ($user) {
            return $user;
        });

        Broadcast::channel('App.User.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });

    }
}
