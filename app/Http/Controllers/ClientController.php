<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Mail\AktivierungsMail;
use App\Models\Activity;
use App\Models\Applicant;
use App\Models\Client;
use App\Models\Creator;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        if(Session::has('jobSession') AND (Session::get('jobSession') !== "" OR Session::get('jobSession') !== null)){
            app('App\Http\Controllers\JobController')->storeJobSession(Session::get('jobSession'));
            Session::forget("jobSession");
            Session::forget("uId");
        }

        // Session mit aktivem client
        $client_id = Session::get('activeClient');

        // offene Jobs
        $jobsOpen = Job::select('clients.name as client', 'jobs.id as id', 'jobs.name as name', 'status_fk as status')
            ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
            ->where('jobs.clients_fk', '=', $client_id)
            ->where('jobs.end', '>=',  DB::raw('curdate()'))
            ->where('jobs.status_fk', '=', 1)
            ->orderBy("jobs.name")
            ->get();

        // aktive Jobs
        $jobsActive = Job::select('clients.name as client', 'jobs.id as id', 'jobs.name as name', 'jobs.end as end', 'status_fk as status', 'creators_fk as creator', 'users.firstname', 'users.lastname', 'users.image', 'users.id as users_id')
            ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
            ->leftJoin('creators', 'creators.id', '=', 'jobs.creators_fk')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->where('jobs.clients_fk', '=', $client_id)
            ->where('jobs.status_fk', '=', 2)
            ->orderBy("jobs.name")
            ->get();

        // abgeschlossene Jobs
        $jobsDone = Job::select('clients.name as client', 'jobs.id as id', 'jobs.name as name', 'status_fk as status')
            ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
            ->where('jobs.clients_fk', '=', $client_id)
            ->where('jobs.status_fk', '=', 3)
            ->orderBy("jobs.name")
            ->get();

        // abgelaufene Jobs
        $jobsExpired = Job::select('clients.name as client', 'jobs.id as id', 'jobs.end', 'jobs.name as name', 'status_fk as status')
            ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
            ->where('jobs.clients_fk', '=', $client_id)
            ->where('jobs.end', '<',  DB::raw('curdate()'))
            ->orWhere('jobs.status_fk', '=', 4)
            ->orderBy("jobs.name")
            ->get();

        $applicants = Applicant::select(DB::raw('count(applicants.creators_id) as applicants'), 'jobs.id')
            ->rightJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
            ->groupBy('jobs.id')
            ->get()
            ->keyBy('id')->toArray();

        $applicantsImages = Applicant::select('applicants.creators_id as creators_id', 'jobs.id as job_id', 'users.image as users_image', 'users.id as users_id')
            ->leftJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
            ->leftJoin('creators', 'creators.id', '=', 'applicants.creators_id')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->get()->toArray();

        // Activities
        $activities = Activity::where("user_id", Auth::id())->orderBy("id", "DESC")->skip(0)->take(15)->get();

        // Projekte vorhanden? (Für Empty State)
        if(count($jobsOpen) == 0 AND count($jobsActive) == 0 AND count($jobsDone) == 0 AND count($jobsExpired) == 0){
            $projects = false;
        } else {
            $projects = true;
        }


        return view("client.dashboard")->with([
            'jobsOpen' => $jobsOpen,
            'jobsActive' => $jobsActive,
            'jobsDone' => $jobsDone,
            'jobsExpired' => $jobsExpired,
            'projects' => $projects,
            'activities' => $activities,
            'applicants' => $applicants,
            'applicantsImages' => $applicantsImages
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $clients = Client::where('users_fk', $user_id)
            ->orderBy('name')
            ->get();
        return view("client.index")->with('clients', $clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("client.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'name' => 'required|min:3|max:50|string|unique:clients',
                'description' => 'required|min:10|string',
                'street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                'plz' => 'required|max:9999|integer',
                'email' => 'required|email|unique:clients',
                'website' => ['required', 'regex:/^((?:(https|http)?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'],
                'telephone' => 'required|regex:/^(\d{9})$/',
            ]
        );

        $user_id = Auth::id();
        $client = new Client(
            [
                'name' => $request['name'],
                'users_fk' => $user_id,
                'description' => $request['description'],
                'street' => $request['street'],
                'plz' => $request['plz'],
                'email' => $request['email'],
                'website' => $request['website'],
                'telephone' => $request['telephone'],
            ]);

        $client->save();
        $client_id = $client->id;

        Session::put('userType', 'client'); // Session mit userType
        Session::put('activeClient', $client_id); // Ausgewählter Client

        $clients = Client::where('users_fk', $user_id)->orderBy('name','asc')->get();
        Session::put('clients', $clients); // Session mit allen Clients

        return $this->index()->with([
            'success' => $request['name'].' erfolgreich erstellt!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $place = DB::table('plz')->where('plz', $client->plz)->first();
        return view("client.show")->with([
            'client' => $client,
            'place' => $place
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        if(HomeController::checkIfUsersClient($client->id)) {
            return view("client.edit")->with('client', $client);
        } else {
            return view("errors.404");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request,
            [
                'name' => 'required|min:3|max:50|string|unique:clients,name,'.$client->id,
                'description' => 'required|min:10|string',
                'street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                'plz' => 'required|max:9999|integer',
                'email' => 'required|email|unique:clients,email,'.$client->id,
                'website' => 'required|url',
                'telephone' => 'required|regex:/^(\d{9})$/',
            ]
        );
        $user_id = Auth::id();
        $client->update(
            [
                'name' => $request['name'],
                'description' => $request['description'],
                'street' => $request['street'],
                'plz' => $request['plz'],
                'email' => $request['email'],
                'website' => $request['website'],
                'telephone' => $request['telephone'],
            ]);
        $client->save();
        return $this->index()->with([
            'success' => $request['name'].' erfolgreich bearbeitet!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        // Session für Select in der Navigation anpassen
        $user_id = Auth::id();
        $clients = Client::where('users_fk', $user_id)->orderBy('name','asc')->get();
        Session::put('clients', $clients); // Session mit allen Clients

        if(count(Session::get("clients")) == 0){
            Session::forget('clients');
            Session::put('userType', "nothing"); // Session mit userType
        }

        $client_id = $client->id;
        $client_name = $client->name;

        if( Session::get('activeClient') == $client_id){
            return redirect()->route('ycity');
        } else {
            return $this->index()->with([
                'success' => $client_name.' erfolgreich gelöscht!'
            ]);
        }


    }

    // Beim Wechsel der Firma Session neu setzen
    public function updateActiveClient(Request $request)
    {
        $client_id = $request->key;
        Session::put('activeClient', $client_id); // Ausgewählter Client
    }

    // Ausgewählter Bewerber dem Auftrag zuweisen
    public function hireApplicant(Request $request)
    {
        $hash = $request['creator_id'];
        $creator_id = HomeController::encrypt_decrypt($hash,"decrypt");

        $hash = $request['job_id'];
        $job_id = HomeController::encrypt_decrypt($hash,"decrypt");

        Job::where("id", $job_id)->update([
            'creators_fk' => $creator_id,
            'status_fk' => 2,
        ]);

        Applicant::where("jobs_id", $job_id)->delete();

        // Activity Table: Creator Eintrag
        $job = Job::select("jobs.name", "users.firstname","users.lastname","users.id as users_id")->leftJoin('creators', 'creators.id', '=', 'jobs.creators_fk')->leftJoin('users', 'users.id', '=', 'creators.users_fk')->where("jobs.id", $job_id)->first();

        $timestamp = date('Y-m-d H:i:s');
        $jobName = $job->name;
        $link = "/ycity/job/".$job_id."/".urlencode($jobName);
        $title = 'It\'s a MATCH!';
        $msg = 'Du wurdest für den Auftrag "'.$jobName.'" angeheuert.';
        $values = array('user_id' => $job->users_id,'type' => 'job','title' => $title, 'message' => $msg, 'link' => $link, 'time' => $timestamp);

        Activity::insert($values);

        // aktiver Creator Notifikation: Creator angeheuert
        broadcast(new NewNotification($values));
    }

    // Jobanfrage an Creator senden
    public function sendRequestToCreator(Request $request)
    {
        $creators_id = $request['creator_id'];

        $job_id = $request['job_id'];
        $jobName = Job::where("id", $job_id)->value("name");

        $link = "/ycity/job/".$job_id."/".urlencode($jobName);
        $title = 'Jobanfrage für "'.$jobName.'" erhalten!';
        $timestamp = date('Y-m-d H:i:s');
        $creator = Creator::select("users.id")->leftJoin('users', 'users.id', '=', 'creators.users_fk')->where("creators.id", $creators_id)->first();


        $values = array('user_id' => $creator->id,'type' => 'job','title' => $title, 'message' => __('main.apply_now'), 'link' => $link, 'time' => $timestamp);
        Activity::insert($values);

        // Creator Notifikation: Jobanfrage
        broadcast(new NewNotification($values));

    }
}
