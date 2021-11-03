<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Applicant;
use App\Models\Category;
use App\Models\Client;
use App\Models\Creator;
use App\Models\Creator_Skill;
use App\Models\Job;
use App\Models\Job_Skills;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return string
     */
    public function index()
    {
        // Zahl der erstellen Jobs im Footer
        if(!Session::has('job_opportunities')){
            $job_opportunities = Job::select("status_fk", "end")->where([
                ['status_fk', '=', 1],
                ['end', '>=',  DB::raw('curdate()')]
            ])->count();
            Session::put('job_opportunities', $job_opportunities); // Session mit allen Clients
        }

        if(Auth::check()){
            $user_id = Auth::id();
            $client_count = User::join('clients','clients.users_fk','=','users.id')
                ->where('users.id', $user_id)
                ->count();
            $creator_count = User::join('creators','creators.users_fk','=','users.id')
                ->where('users.id', $user_id)
                ->count();

            if($client_count >= 1){
                $type = 'client';
            } else if($creator_count >= 1){
                $type = 'creator';
            } else {
                $type = 'nothing';
            }
            Session::put('userType', $type);
        }

        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::select('id', 'name', 'categories_fk')
            ->orderBy('name')
            ->get();

        $currentPath= Route::getFacadeRoot()->current()->uri();
        if($currentPath == "creator"){
            return view("indexCreator")->with([
                'categories' => $categories,
                'subcategories' => $subcategories,
            ]);
        } else {
            return view("indexClient")->with([
                'categories' => $categories,
                'subcategories' => $subcategories,
            ]);
        }

    }

    public function redirect(){
        if(Auth::check()){
            /* Session userType: gibt an, ob Benutzer client, creator oder noch nichts ist. */
            $user_id = Auth::id();
            $mode = Auth::User()->dark_mode;
            Session::put('dark_mode', $mode);

            $client_count = User::join('clients','clients.users_fk','=','users.id')
                ->where('users.id', $user_id)
                ->count();
            $creator_count = User::join('creators','creators.users_fk','=','users.id')
                ->where('users.id', $user_id)
                ->count();

            $job_opportunities = Job::select("status_fk", "end")->where([
                ['status_fk', '=', 1],
                ['end', '>=',  DB::raw('curdate()')]
            ])->count();
            Session::put('job_opportunities', $job_opportunities); // Session mit allen Clients
            if($client_count >= 1){
                $type = 'client';
                $clients = Client::where('users_fk', $user_id)->orderBy('name','asc')->get();
                Session::put('clients', $clients); // Session mit allen Clients
                Session::put('userType', $type);
                if(!Session::has('activeClient')){
                    Session::put('activeClient', $clients[0]['id']); // Ausgewählter Client
                }
                return app('App\Http\Controllers\ClientController')->dashboard();
            } else if($creator_count >= 1){
                $type = 'creator';

                $creator_id = Creator::where("users_fk", $user_id)->value("id");
                Session::put('creator_id', $creator_id); // Session mit creator_id
                Session::put('userType', $type);
                return app('App\Http\Controllers\CreatorController')->dashboard();
            } else {
                $type = 'nothing';
                Session::put('userType', $type);
                return view('landingpage');
            }
        } else {
            return route('home');
        }
    }

    // Ort anhand der PLZ aus der DB lesen
    public function getPlaceFromDB(Request $request)
    {
        $plz = $request->key;
        $place = DB::table('plz')->where('plz', '=', $plz)->value("place");

        return $place;
    }

    // Encrypt und Decrypt Funktion, um sensible Daten zu verschlüsseln
    public static function encrypt_decrypt($string, $action = 'encrypt')
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'f1EIt5EOlHGF178NIFh9UWr23BhBVv'; // user define private key
        $secret_iv = '10d8G6k4YkL0'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    /**
     * Check if Job is assigned to active Client.
     *
     */
    public static function checkIfUsersJob($job_id)
    {
        $job_count = Job::where([
            ['id', '=', $job_id],
            ['clients_fk', '=', Session::get('activeClient')],
        ])->count();

        if($job_count == 0){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if Client is assigned to active User.
     *
     */
    public static function checkIfUsersClient($client_id)
    {
        $user_id = Auth::id();
        $client_count = Client::where([
            ['id', '=', $client_id],
            ['users_fk', '=', $user_id],
        ])->count();

        if($client_count == 0){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if Creator has applied to job.
     *
     */
    public static function checkIfCreatorAppliedToJob($creators_id, $jobs_id)
    {
        $applicant_count = Applicant::where([
            ['creators_id', '=', $creators_id],
            ['jobs_id', '=', $jobs_id],
        ])->count();

        if($applicant_count == 0){
            return false;
        } else {
            return true;
        }
    }
    /**
     * Check if Creator has applied to job.
     *
     */
    public static function checkIfCreatorIsAssignedToJob($creators_id, $jobs_id)
    {
        $job_count = Job::where([
            ['creators_fk', '=', $creators_id],
            ['id', '=', $jobs_id],
        ])->count();

        if($job_count == 0){
            return false;
        } else {
            return true;
        }
    }

    /**
     * Change unseen to seen of every message from logged user.
     *
     */
    public static function clearUnseenMessages()
    {
        Activity::where([
            ['user_id', '=', Auth::id()],
            ['seen', '=', '0'],
        ])->update(['seen' => 1]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getActiveSkills(Request $request)
    {
        if($request["type"] == "job"){
            // aktive Skills vom Job herauslesen
            $activeSkills = Job_skills::select("skills.id")
                ->leftJoin('skills', 'skills.id', '=', 'job_skills.skills_id')
                ->leftJoin('jobs', 'jobs.id', '=', 'job_skills.jobs_id')
                ->where("jobs_id", $request["id"])->get();

            $skillsArray = array();
            foreach($activeSkills as $skills){
                $id = HomeController::encrypt_decrypt($skills->id,"encrypt");
                $skillsArray[] = $id;
            }
        } else if($request["type"] == "creator"){
            // aktive Skills vom Creator herauslesen
            $activeSkills = Creator_Skill::select("skills.id")
                ->leftJoin('skills', 'skills.id', '=', 'creator_skills.skills_id')
                ->leftJoin('creators', 'creators.id', '=', 'creator_skills.creators_id')
                ->where("creators_id", $request["id"])->get();

            $skillsArray = array();
            foreach($activeSkills as $skills){
                $id = HomeController::encrypt_decrypt($skills->id,"encrypt");
                $skillsArray[] = $id;
            }
        }

        return $skillsArray;
    }

    // File anzeigen (wird nicht gebraucht)
    public function showFile()
    {
        if(isset($_GET["src"])){
            return response()->file(asset("/uploads/clients/3/54/Personalienblatt_10218_12448.pdf"));
        }
    }

    /**
     * Chat Benachrichtigung nur dann der Activity Table hinzufügen, wenn der Chat nicht offen ist (NotificationComponent)
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function insertActivityIfChatNotOpen(Request $request)
    {
        $values = array('user_id' => $request['user_id'],'type' => $request['type'],'title' => $request['title'], 'message' => $request['message'], 'link' => $request['link'], 'time' => $request['timestamp']);

        if(Activity::insert($values)){
            return 1;
        } else {
            return 0;
        }
    }
}
