<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Creator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //compose all the views....
        view()->composer('*', function ($view)
        {
            // wenn eingeloggt, neuste Activites aus der Activity Table des eingeloggten Benutzers holen
            if(\Auth::check()){
                // Activities
                $activities = Activity::where("user_id", Auth::id())->orderBy("id", "desc")->skip(0)->take(15)->get();
                Session::put('activities', $activities); // Session mit userType

                if(Session::get('userType') == 'creator'){
                    $active = Creator::where("users_fk", Auth::id())->value("activated");
                    Session::put('activated', $active);
                }
            } else {
                Session::forget('activities');
                Session::forget('activated');
            }
        });

        $this->registerPolicies();

        //
    }
}
