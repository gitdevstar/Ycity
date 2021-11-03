<?php

namespace App\Http\Controllers;

use App\Mail\AbsageMail;
use App\Mail\AktivierungsMail;
use App\Mail\WillkommensMail;
use App\Models\Activity;
use App\Models\Applicant;
use App\Models\Creator;
use App\Models\Creator_Skill;
use App\Models\Job;
use App\Models\Job_Skills;
use App\Models\Skill;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use DirectoryIterator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CreatorController extends Controller
{

    public function creatorType()
    {
        return view("landingpageCreator");
    }

    public function dashboard()
    {
        $creator_id = Session::get('creator_id');

        // beworbene Jobs
        $jobsApplied = Applicant::select("jobs.name","jobs.id")
            ->leftJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
            ->where("applicants.creators_id", $creator_id)
            ->orderBy("jobs.name")
            ->get();

        // aktive Jobs
        $jobsActive = Job::select("jobs.name","jobs.id","jobs.end")->where([
            ['jobs.creators_fk', '=', $creator_id],
            ['jobs.status_fk', '=', 2],
            ['jobs.end', '>=',  DB::raw('curdate()')]
        ])->orderBy("jobs.name")->get();

        // abgeschlossene Jobs
        $jobsDone = Job::select("jobs.name","jobs.id")->where([
            ['jobs.creators_fk', '=', $creator_id],
            ['jobs.status_fk', '=', 3],
        ])->orderBy("jobs.name")->get();

        // abgelaufene Jobs
        $jobsExpired = Job::select("jobs.name","jobs.id")
            ->where([
                ['jobs.creators_fk', '=', $creator_id],
                ['jobs.status_fk', '=', 4]
            ])->orWhere([
                ['jobs.creators_fk', '=', $creator_id],
                ['jobs.end', '<',  DB::raw('curdate()')]
            ])->orderBy("jobs.name")->get();

        // Activities
        $activities = Activity::where("user_id", Auth::id())->orderBy("id", "DESC")->skip(0)->take(15)->get();

        // Bewerber
        $applicants = Applicant::select(DB::raw('count(applicants.creators_id) as applicants'), 'jobs.id')
            ->rightJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
            ->groupBy('jobs.id')
            ->get()
            ->keyBy('id')->toArray();

        // Bilder der Bewerber
        $applicantsImages = Applicant::select('applicants.creators_id as creators_id', 'jobs.id as job_id', 'users.image as users_image', 'users.id as users_id')
            ->leftJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
            ->leftJoin('creators', 'creators.id', '=', 'applicants.creators_id')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->get()
            ->toArray();

        // Projekte vorhanden? (Für Empty State)
        if(count($jobsApplied) == 0 AND count($jobsActive) == 0 AND count($jobsDone) == 0 AND count($jobsExpired) == 0){
            $projects = false;
        } else {
            $projects = true;
        }

        // Portfolio vorhanden?
        $dir = storage_path('app/private').'/uploads/creators/'.$creator_id.'/portfolio';
        if(File::isDirectory($dir)){
            $portfolio = true;
        } else {
            $portfolio = false;
        }

        // Skills vorhanden?
        $skillsCount = Creator_Skill::all()->where("creators_id", $creator_id)->count();


        $userImage = Auth::user()->image;

        return view("creator.dashboard")->with([
            'jobsApplied' => $jobsApplied,
            'jobsActive' => $jobsActive,
            'jobsDone' => $jobsDone,
            'jobsExpired' => $jobsExpired,
            'projects' => $projects,
            'activities' => $activities,
            'applicants' => $applicants,
            'applicantsImages' => $applicantsImages,
            'portfolio' => $portfolio,
            'skillsCount' => $skillsCount,
            'userImage' => $userImage,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("creator.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skills = Skill::select('id', 'name')
            ->orderBy('name')
            ->get();

        if(Session::get('userType') == 'creator'){
            return redirect("/ycity");
        } else {
            if(isset($_GET["type"]) AND $_GET["type"] == "company"){
                return view("creator.create")->with([
                    'skills' => $skills,
                    'type' => 'company',
                ]);
            } else if(isset($_GET["type"]) AND $_GET["type"] == "no-company"){
                return view("creator.create")->with([
                    'skills' => $skills,
                    'type' => 'no-company',
                ]);
            } else {
                return redirect("/ycity");
            }

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
       // $request['users_fk'] = $user_id;

        if($request["creatorType"] == "company"){

            $this->validate($request,
                [
                    'users_fk' => 'unique:creators,users_fk',
                    'street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                    'plz' => 'required|max:9999|integer',
                    'birthdate' => 'required|before:' . date('d-m-Y'),
                    'description' => 'required|min:10|string',
                    'telephone' => 'required|regex:/^(\d{9})$/',
                    'website' => 'nullable|regex:/^((?:(https|http)?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/',
                    'apply_text' => 'required|string|min:10',
                    'organisation_type' => 'required',
                    'organisation_name' => 'required|regex:/^([0-9a-zA-ZäöüÄÖÜ\- ])+$/',
                    'organisation_uid' => 'required|regex:/^\d{3}\.\d{3}\.\d{3}$/',
                    'organisation_sva' => 'required',
                ]
            );
        } else {
            $this->validate($request,
                [
                    'users_fk' => 'unique:creators,users_fk',
                    'street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                    'plz' => 'required|max:9999|integer',
                    'birthdate' => 'required|before:' . date('d-m-Y'),
                    'description' => 'required|min:10|string',
                    'telephone' => 'required|regex:/^(\d{9})$/',
                    'website' => 'nullable|regex:/^((?:(https|http)?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/',
                    'apply_text' => 'required|string|min:10',
                    'children' => 'required',
                    'ahv_nr' => 'required|regex:/^\d{4}\.\d{4}\.\d{2}$/',
                    'nationality' => 'required|string',
                ]
            );
        }

        $creator = new Creator(
            [
                'users_fk' => $user_id,
                'birthdate' => $request['birthdate'],
                'description' => $request['description'],
                'street' => $request['street'],
                'plz' => $request['plz'],
                'telephone' => $request['telephone'],
            ]);

        $creator->save();
        $creators_id = $creator->id;

        // Skills dem Creator hinzufügen
        if($request['skills_text'] !== "-"){
            $skills = explode(",", $request['skills']);
            // Skills iterieren und in DB schreiben
            foreach($skills as $skill){
                $skill = HomeController::encrypt_decrypt($skill,"decrypt");
                $creator_skill = new creator_skill(
                    [
                        'creators_id' => $creators_id,
                        'skills_id' => $skill,
                    ]);
                $creator_skill->save();
            }
        }

        Session::put('userType', 'creator'); // Session mit userType


        if($request["creatorType"] == "company"){
            // Selbständiger Creator
            $firstname = Auth::user()->firstname;
            $lastname = Auth::user()->lastname;
            $creators_hash = HomeController::encrypt_decrypt($creators_id, "encrypt");
            $users_hash = HomeController::encrypt_decrypt($user_id, "encrypt");

            // Email Daten aufbereiten
            $emailData = [
                'type' => "company",
                'firstname' => $firstname,
                'lastname' => $lastname,
                'cId_hash' => $creators_hash,
                'uId_hash' => $users_hash,
                'domain' => env('APP_URL', 'http://localhost'),
            ];

            // PDF Daten aufbereiten
            $pdfData = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'birthdate' => $request['birthdate'],
                'description' => $request['description'],
                'street' => $request['street'],
                'plz' => $request['plz'],
                'telephone' => $request['telephone'],
                'apply_text' => $request["apply_text"],
                'website' => $request["website"],
                'organisation_type' => $request["organisation_type"],
                'organisation_name' => $request["organisation_name"],
                'organisation_uid' => $request["organisation_uid"],
                'skills_text' => $request["skills_text"],
            ];

            // PDF erstellen und ablegen
            $pdf = PDF::loadView('pdf.s_creator', array('pdfData' => $pdfData) );
            Storage::put("private/uploads/creators/".Auth::id()."/info.pdf", $pdf->output());

            // Mail auslösen
            $defaultMail = env('MAIL_FROM_ADDRESS', 'hi@ycity.ch');
            Mail::to($defaultMail)->send(new AktivierungsMail($emailData));
        } else {
            // Nicht selbständiger Creator
            $firstname = Auth::user()->firstname;
            $lastname = Auth::user()->lastname;
            $creators_hash = HomeController::encrypt_decrypt($creators_id, "encrypt");
            $users_hash = HomeController::encrypt_decrypt($user_id, "encrypt");

            // Email Daten aufbereiten
            $emailData = [
                'type' => "no company",
                'firstname' => $firstname,
                'lastname' => $lastname,
                'cId_hash' => $creators_hash,
                'uId_hash' => $users_hash,
                'domain' => env('APP_URL', 'http://localhost'),
            ];

            // PDF Daten aufbereiten
            $pdfData = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'birthdate' => $request['birthdate'],
                'description' => $request['description'],
                'street' => $request['street'],
                'plz' => $request['plz'],
                'telephone' => $request['telephone'],
                'apply_text' => $request["apply_text"],
                'website' => $request["website"],
                'ahv_nr' => $request["ahv_nr"],
                'children' => $request["children"],
                'nationality' => $request["nationality"],
                'skills_text' => $request["skills_text"],
            ];

            // PDF erstellen und ablegen
            $pdf = PDF::loadView('pdf.ns_creator', array('pdfData' => $pdfData) );
            Storage::put("private/uploads/creators/".Auth::id()."/info.pdf", $pdf->output());

            // Mail auslösen
            $defaultMail = env('MAIL_FROM_ADDRESS', 'hi@ycity.ch');
            Mail::to($defaultMail)->send(new AktivierungsMail($emailData));
        }

    }

    // SVA Bescheinigung abspeichern
    public function storeSVA(Request $request) {
        if($request->hasFile('filepond')){
            $file = $request->file('filepond');
            $file->storeAs("private/uploads/creators/".Auth::id(), "sva.pdf");
        }
    }

    // SVA Bescheinigung löschen
    public function deleteSVA(Request $request) {
        $dir = storage_path('app/private/uploads/creators/'.Auth::id());
        if(file_exists($dir."/sva.pdf")){
            unlink($dir."/sva.pdf");
            return true;
        } else {
            return false;
        }
    }

    public function validateCreatorInfos(Request $request) {
        $this->validate($request,
            [
                'street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                'plz' => 'required|max:9999|integer',
                'birthdate' => 'required|before:' . date('d-m-Y'),
                'description' => 'required|min:10|string',
                'telephone' => 'required|regex:/^(\d{9})$/',
            ]
        );
    }

    public function validatePreCreatorInfos(Request $request) {
        if($request["creatorType"] == "company"){
            $this->validate($request,
                [
                    'organisation_type' => 'required',
                    'organisation_name' => 'required|regex:/^([0-9a-zA-ZäöüÄÖÜ\- ])+$/',
                    'organisation_uid' => 'required|regex:/^\d{3}\.\d{3}\.\d{3}$/',
                    'organisation_sva' => 'required',
                    /*'organisation_street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                    'organisation_plz' => 'required|max:9999|integer',
                    'organisation_telephone' => 'required|regex:/^(\d{9})$/',*/
                ]
            );
        } else {
            $this->validate($request,
                [
                    'children' => 'required',
                    'ahv_nr' => 'required|regex:/^\d{4}\.\d{4}\.\d{2}$/',
                    'nationality' => 'required|string',
                ]
            );
        }
    }

    public function applyCreator() {
       if((isset($_GET["cId"]) AND $_GET["cId"] !== "") and (isset($_GET["uId"]) AND $_GET["uId"] !== "")){
           $creators_id = HomeController::encrypt_decrypt($_GET["cId"], "decrypt");
           $activated = Creator::where("id", $creators_id)->value("activated");

           if(!$activated){
               $users_id = HomeController::encrypt_decrypt($_GET["uId"], "decrypt");

               Creator::where("id", $creators_id)->update(['activated' => 1]);
               $email = User::where("id",$users_id)->value("email");

               // Mail auslösen
               Mail::to($email)->send(new WillkommensMail());

               return view("auth.creator_applied");
           } else {
               return view("errors.404");
           }

       } else {
           return view("errors.404");
       }
    }

    public function denyCreator (Request $request) {
        if((isset($_GET["cId"]) AND $_GET["cId"] !== "") and (isset($_GET["uId"]) AND $_GET["uId"] !== "")){
            $creators_id = HomeController::encrypt_decrypt($_GET["cId"], "decrypt");
            $activated = Creator::where("id", $creators_id)->value("activated");

            if(!$activated){
                $users_id = HomeController::encrypt_decrypt($_GET["uId"], "decrypt");
                $email = User::where("id",$users_id)->value("email");
                User::where("id", $users_id)->delete();

                // Mail auslösen
                Mail::to($email)->send(new AbsageMail());

                return view("auth.creator_denied");
            } else {
                return view("errors.404");
            }
        } else {
            return view("errors.404");
        }
    }

    /**
     * Display portfolio of the creator.
     *
     * @param  \App\Models\Creator  $creator
     * @return \Illuminate\Http\Response
     */
    public function show(Creator $creator)
    {
        $creators_id = $creator->id;

        // Creator
        $creator = Creator::select('users.firstname','users.lastname','users.image','plz.canton_DE','plz.canton_short','users.id as users_id','creators.id as creators_id','creators.description')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')
            ->where('creators.id', $creators_id)
            ->get()
            ->first();

        // Skills herauslesen
        $skills = Creator_Skill::select("skills.name")->leftJoin('skills', 'skills.id', '=', 'creator_skills.skills_id')->where("creators_id", $creator->creators_id)->orderBy("skills.name")->get();

        // Portfolio Ordner durchgehen und Name in Array speichern
        $images = array();
        $path = storage_path('app/private/uploads/creators/'.$creators_id.'/portfolio');

        if(File::isDirectory($path)) {
            $files = File::allFiles($path);
            foreach($files as $file) {
                $filename = $file->getFilename();
                $images[] = $filename;
            }

        }

        if(Session::get('userType') == 'client'){

            // Creator Suche Auswahl für Job => Job ID mitgeben
            if(isset($_GET["job"])) {
                $job_id = HomeController::encrypt_decrypt($_GET["job"], "decrypt");
                if(HomeController::checkIfUsersJob($job_id)){
                    $job_hire_id = $job_id;
                } else {
                    $job_hire_id = null;
                }
            } else {
                $job_hire_id = null;
            }

            $appliedJobs = Applicant::select('jobs.name', 'jobs.id', 'jobs.clients_fk')
                ->leftJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
                ->leftJoin('creators', 'creators.id', '=', 'applicants.creators_id')
                ->where('creators.id', $creators_id)
                ->get();
            return view("creator.portfolio")->with([
                'creator' => $creator,
                'images' => $images,
                'appliedJobs' => $appliedJobs,
                'skills' => $skills,
                'job_hire_id' => $job_hire_id,
            ]);
        } else {
            return view("creator.portfolio")->with([
                'creator' => $creator,
                'images' => $images,
                'skills' => $skills,
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Creator  $creator
     * @return \Illuminate\Http\Response
     */
    public function edit(Creator $creator)
    {
        return view("creator.edit")->with('creator', $creator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Creator  $creator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Creator $creator)
    {
        $this->validate($request,
            [
                'street' => 'required|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                'plz' => 'required|max:9999|integer',
                'email' => 'required|email|unique:creators,email,'.$creator->id,
                'telephone' => 'required|regex:/^(\d{9})$/',
            ]
        );

        $user_id = Auth::id();
        $creator->update(
            [
                'street' => $request['street'],
                'plz' => $request['plz'],
                'email' => $request['email'],
                'telephone' => $request['telephone'],
            ]);
        $creator->save();
        return $this->index()->with([
            'success' => 'Creator erfolgreich bearbeitet!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Creator  $creator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Creator $creator)
    {
        $creator->delete();
    }

    /**
     * Update the skills of the creator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchCreators(Request  $request)
    {
        if($_GET["job"]){
            $job_id = HomeController::encrypt_decrypt($_GET["job"],"decrypt");
            if(HomeController::checkIfUsersJob($job_id)){
                // Session mit userType
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
                $activities = Activity::where("user_id", Auth::id())->orderBy("id", "DESC")->get();

                // Projekte vorhanden? (Für Empty State)
                if(count($jobsOpen) == 0 AND count($jobsActive) == 0 AND count($jobsDone) == 0 AND count($jobsExpired) == 0){
                    $projects = false;
                } else {
                    $projects = true;
                }

                $creators = Creator::select(DB::raw('GROUP_CONCAT( skills.name SEPARATOR ", ") as skills'), 'creators.id as creators_id', 'users.firstname','users.lastname','users.image','users.id as users_id', 'plz.plz', DB::raw('max(plz.place) as place'), DB::raw('max(plz.canton_DE) as canton'), DB::raw('max(plz.canton_short) as canton_short'))
                ->leftJoin('creator_skills', 'creator_skills.creators_id', '=', 'creators.id')
                ->leftJoin('skills', 'skills.id', '=', 'creator_skills.skills_id')
                ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
                ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')
                ->orderBy('users.firstname')
                ->groupBy('creators.id')
                ->get();

                return view("creator.searchCreators")->with(
                    [
                        'creators' => $creators,
                        'job_id' => $job_id,
                        'job_hash' => $_GET["job"],
                        'jobsOpen' => $jobsOpen,
                        'jobsActive' => $jobsActive,
                        'jobsDone' => $jobsDone,
                        'jobsExpired' => $jobsExpired,
                        'projects' => $projects,
                        'activities' => $activities,
                        'applicants' => $applicants,
                        'applicantsImages' => $applicantsImages
                    ]);
            } else {
                return view("errors.404");
            }

        } else {
            return view("errors.404");
        }
    }

    /**
     * Jobs auf Text, Status und Kategorie filtern und zurückgeben.
     */
    public function getCreators(Request $request)
    {
        $job_id = HomeController::encrypt_decrypt($request["job_hash"], "decrypt");
        if (HomeController::checkIfUsersJob($job_id)) {
            if ($request["term"] !== null) {
                $creators = Creator::select(DB::raw('GROUP_CONCAT( skills.name SEPARATOR ", ") as skills'), 'creators.id as creators_id', 'users.firstname','users.lastname','users.image','users.id as users_id', 'plz.plz', DB::raw('max(plz.place) as place'), DB::raw('max(plz.canton_DE) as canton'), DB::raw('max(plz.canton_short) as canton_short'))
                    ->leftJoin('creator_skills', 'creator_skills.creators_id', '=', 'creators.id')
                    ->leftJoin('skills', 'skills.id', '=', 'creator_skills.skills_id')
                    ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
                    ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')
                    ->orderBy('users.firstname')
                    ->groupBy('creators.id')
                    ->having('skills', 'like', '%'.$request["term"].'%')
                    ->orHaving('users.firstname', 'like', '%'.$request["term"].'%') // Vorname
                    ->orHaving('users.lastname', 'like', '%'.$request["term"].'%') // Nachname
                    ->orHaving('place', 'like', '%'.$request["term"].'%') // Ort
                    ->orHaving('canton', 'like', '%'.$request["term"].'%') // Kanton
                    ->get();
            } else {
                $creators = Creator::select(DB::raw('GROUP_CONCAT( skills.name SEPARATOR ", ") as skills'), 'creators.id as creators_id', 'users.firstname','users.lastname','users.image','users.id as users_id', 'plz.plz', DB::raw('max(plz.place) as place'), DB::raw('max(plz.canton_DE) as canton'), DB::raw('max(plz.canton_short) as canton_short'))
                    ->leftJoin('creator_skills', 'creator_skills.creators_id', '=', 'creators.id')
                    ->leftJoin('skills', 'skills.id', '=', 'creator_skills.skills_id')
                    ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
                    ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')
                    ->orderBy('users.firstname')
                    ->groupBy('creators.id')
                    ->get();
            }

            $output = "";
            if(count($creators) !== 0){
                $output .= '<div class="row m-0"><div class="col-md-12 p-0 w-100 ">';
                foreach($creators as $creator) {
                    if (is_dir($_SERVER["DOCUMENT_ROOT"] . "/images/profiles/" . $creator->users_id)){
                        $imgLink = "/images/profiles/".$creator->users_id."/".$creator->image;
                    } else {
                        $imgLink = "/images/profiles/".$creator->image;
                    }

                    if(isset($creator->skills)){
                        $skills = " - ".__("main.skills").": ".$creator->skills;
                    } else {
                        $skills = "";
                    }

                    $output .= '<a class="mb-2 d-block" href="/ycity/creator/'.$creator->creators_id.'?id='.$creator->creators_id.'&firstname='.$creator->firstname.'&lastname='.$creator->lastname.'&job='.$request["job_hash"].'">
                            <div class="creatorBox">
                                <div class="row m-0">
                                    <div class="col-1 p-0">
                                    <img class="creatorImage" src="'.$imgLink.'" />
                                    </div>
                                    <div class="col-9 p-0 position-relative">
                                        <span class="creatorName">'.$creator->firstname.' '.$creator->lastname.'</span>
                                        <span class="creatorDescription d-block">'.$creator->place.' '.$creator->canton_short.$skills.'</span>
                                    </div>
                                </div>
                            </div>
                        </a>';


                }
                $output .= '</div></div>';
            } else {
                $output .= '<p>'.__("creatorlist.no_creator_found").'</p>';
            }

            return $output;
        } else {
            return view("errors.404");
        }
    }

    /**
     * Edit the skills of the creator
     *
     */
    public function editSkills()
    {
        $creators_id = Session::get('creator_id');
        $skills = Skill::select('id', 'name')
            ->orderBy('name')
            ->get();

        // Skills herauslesen
        $activeSkills = Creator_Skill::select("skills.id")
            ->leftJoin('skills', 'skills.id', '=', 'creator_skills.skills_id')
            ->leftJoin('creators', 'creators.id', '=', 'creator_skills.creators_id')
            ->where("creators_id", $creators_id)
            ->get()->keyBy('id')->toArray();

        return view("creator.editSkills")->with(
        [
            'activeSkills' => $activeSkills,
            'skills' => $skills,
            'creators_id' => $creators_id
        ]);
    }

    /**
     * Update the skills of the creator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Creator  $creator
     * @return \Illuminate\Http\Response
     */
    public function updateCreatorSkills(Request  $request)
    {
        $creators_id = HomeController::encrypt_decrypt($request["id"],"decrypt");

        // alte Skills löschen
        $creator_skills=creator_skill::where("creators_id", $creators_id)->delete();

        // Skills dem Creator hinzufügen
        if($request['skills'] !== ""){
            // Skills iterieren und in DB schreiben
            foreach($request['skills'] as $skill){
                $skill = HomeController::encrypt_decrypt($skill,"decrypt");
                $creator_skill = new creator_skill(
                    [
                        'creators_id' => $creators_id,
                        'skills_id' => $skill,
                    ]);
                $creator_skill->save();
            }
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Edit the portfolio files.
     *
     * @param  \App\Models\Creator  $creator
     * @return \Illuminate\Http\Response
     */
    public function editPortfolio(Creator $creator)
    {
        $creators_id = Session::get('creator_id');
        $creator = Creator::select('users.firstname','users.lastname','users.image','plz.canton_DE','plz.canton_short','users.id as user_id','creators.id as creators_id','creators.description')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')
            ->where('creators.id', $creators_id)
            ->get()
            ->first();

        // Portfolio Ordner durchgehen und Name in Array speichern
        $dir = storage_path('app/private').'/uploads/creators/'.$creators_id.'/portfolio';
        $images = array();
        if(File::isDirectory($dir)){
            $files = new DirectoryIterator($dir);
            foreach ($files as $file) {
                if($file->isDot()) continue;
                $filename = $file->getFilename();
                $images[] = $filename;
            }
        }

        // Temp Portfolio Ordner löschen
        if(File::isDirectory(storage_path('app/private').'/uploads/creators/'.$creators_id.'/temp')){
            File::deleteDirectory(storage_path('app/private').'/uploads/creators/'.$creators_id.'/temp');
        }

        return view("creator.editPortfolio")->with(
            [
                'images' => $images,
                'creator' => $creator
            ]);
    }

    /**
     * Upload a temp portfolio file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTempPortfolioUpload(Request $request)
    {

        if ($request->isMethod('post')) {
            $creators_id = Session::get('creator_id');
            $filename = $request->filename;
            $file = $request->file;
            $dir = storage_path('app/private').'/uploads/creators/'.$creators_id;

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            if(!file_exists($dir."/temp")){
                mkdir($dir.'/temp', 0777, true);
            }

            file_put_contents($dir."/temp/".$filename, file_get_contents($file));
        }
    }

    /**
     * Delete a uploaded temp portfolio file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTempPortfolioUpload(Request $request)
    {
        if ($request->isMethod('post')) {
            $creators_id = Session::get('creator_id');
            $filename = $request->filename;
            $dir = storage_path('app/private').'/uploads/creators/'.$creators_id;
            if(file_exists($dir."/temp/".$filename)){
                unlink($dir."/temp/".$filename);

            }
        }
    }

    /**
     * Delete a uploaded temp portfolio file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTempPortfolioUploads(Request $request)
    {
        if ($request->isMethod('post')) {
            $creators_id = Session::get('creator_id');
            $this->moveTempFiles($creators_id);
        }
    }
    /**
     * Rename temp folder with portfolio files to the id of the creator and store it in portfolio folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveTempFiles($creators_id)
    {
        // prüfen, ob Portfolio Ordner bereits existiert
        if(!File::isDirectory(storage_path('app/private').'/uploads/creators/'.$creators_id.'/portfolio')){
            $dir = storage_path('app/private').'/uploads/creators/'.$creators_id.'/temp';
            File::move($dir, storage_path('app/private').'/uploads/creators/'.$creators_id.'/portfolio');
        } else {
            $dir = storage_path('app/private').'/uploads/creators/'.$creators_id;
            // output all files and directories except for '.' and '..'
            foreach (new DirectoryIterator($dir."/temp") as $fileInfo) {
                if($fileInfo->isDot()) continue;
                $filename = $fileInfo->getFilename();
                File::move($dir."/temp/".$filename, $dir."/portfolio/".$filename);
            }
        }

    }


    /**
     * Delete a uploaded temp portfolio file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deletePortfolioFile(Request $request)
    {
        if ($request->isMethod('post')) {
            $creators_id = HomeController::encrypt_decrypt($request["creator"],"decrypt");
            $image = HomeController::encrypt_decrypt($request["image"],"decrypt");
            $dir = storage_path('app/private').'/uploads/creators/'.$creators_id;
            if(file_exists($dir."/portfolio/".$image)){
                unlink($dir."/portfolio/".$image);
            }
        }
    }
}
