<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class checkClientAccess
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
        if(Session::get('userType') == 'client'){
            // CLients ermitteln
            $user_id = Auth::id();
            $client_count = DB::table('users')
                ->join('clients','clients.users_fk','=','users.id')
                ->where('users.id', $user_id)
                ->count();

            if($client_count >= 1){
                $clients = Client::where('users_fk', $user_id)->orderBy('name','asc')->get();
                Session::put('clients', $clients); // Session mit allen Clients
                if(!Session::has('activeClient')){
                    Session::put('activeClient', $clients[0]['id']); // Ausgewählter Client
                }
            }

            Session::forget('creator_id'); // Session mit creator_id

            return $next($request);
        } else {
            return redirect("/ycity");
        }



    }
}
