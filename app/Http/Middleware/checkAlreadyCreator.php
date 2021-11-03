<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Creator;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class checkAlreadyCreator
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
        if(Session::has('creator_id')){
            return redirect("/ycity");
        } else {
            return $next($request);
        }
    }
}
