<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Creator;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class checkCreatorAccess
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
        if(Session::get('userType') == 'creator' AND Session::get("activated")){
            $creator_id = Creator::where("users_fk", Auth::id())->value("id");
            Session::put('creator_id', $creator_id); // Session mit creatorId
            Session::forget('clients'); // Session mit allen Clients
            Session::forget('activeClient'); // Session mit allen Clients

            return $next($request);
        } else {
            return redirect("/ycity");
        }
    }
}
