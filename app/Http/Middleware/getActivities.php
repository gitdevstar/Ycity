<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class getActivities
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
        // Activities
        $activities = Activity::where("user_id", Auth::id())->get();

        Session::put('activities', $activities); // Session mit userType

        return $next($request);
    }
}
