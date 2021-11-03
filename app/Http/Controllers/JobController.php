<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Mail\RechnungsMail;
use App\Models\Activity;
use App\Models\Applicant;
use App\Models\Category;
use App\Models\Client;
use App\Models\Creator;
use App\Models\Job;
use App\Models\Job_Skills;
use App\Models\Job_Specification;
use App\Models\Skill;
use App\Models\Specification;
use App\Models\Status;
use App\Models\Subcategory;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_id = Session::get('activeClient'); // Session mit aktivem Client
        $jobs = DB::table('users')
            ->select('clients.name as client', 'jobs.id as id', 'jobs.name as name', 'status.name as status')
            ->leftJoin('clients', 'users.id', '=', 'clients.users_fk')
            ->leftJoin('jobs', 'clients.id', '=', 'jobs.clients_fk')
            ->leftJoin('status', 'status.id', '=', 'jobs.status_fk')
            ->where('jobs.clients_fk', '=', $client_id)
            ->orderBy('clients.name')
            ->get();

        return view("job.index")->with('jobs', $jobs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check() AND Session::get("userType") == "creator") {
            return redirect()->route('ycity');
        }
        /*
        $skills = Skill::select('id', 'name', 'categories_fk')
            ->orderBy('name')
            ->get();
        */

        // Kategorien aus der DB lesen
        $categories = \DB::table('categories')
            ->select('id', 'name', 'icon')
            ->orderBy('name')
            ->get();

        // Kategorien aus der DB lesen
        $subcategories = \DB::table('subcategories')
            ->select('id', 'name', 'icon', 'cost', 'categories_fk')
            ->orderBy('name')
            ->get();

        // Komplexitäten aus der DB lesen
        $complexities = \DB::table('complexities')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        // Zahlungsmöglichkeiten aus der DB lesen
        $payment_types = \DB::table('payment_types')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        if(Auth::check()){
            // Session mit activer Client
            $client_id = Session::get('activeClient');

            // Client Name aus der DB lesen
            $client = DB::table('clients')->where('id', $client_id)->pluck('name');
            $client_name = $client[0];

            if(File::isDirectory(storage_path('app/private').'/uploads/clients/'.$client_id.'/temp')){
                File::deleteDirectory(storage_path('app/private').'/uploads/clients/'.$client_id.'/temp');
            }


            return view("job.create")->with([
                'client' => $client_name,
                'categories' => $categories,
                'subcategories' => $subcategories,
                //'skills' => $skills,
                'complexities' => $complexities,
                'payment_types' => $payment_types,
            ]);
        } else {
            Session::put('uId', uniqid());
            $uId = Session::get('uId');

            return view("job.create")->with([
                'uId' => $uId,
                'categories' => $categories,
                'subcategories' => $subcategories,
                //'skills' => $skills,
                'complexities' => $complexities,
                'payment_types' => $payment_types,
            ]);
        }


    }

    /**
     * Upload a temp job attachment file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTempJobAttachments(Request $request)
    {
        if ($request->isMethod('post')) {

            if(!Auth::check()) {
                $folder = Session::get('uId');

                $filename = $request->filename;
                $file = $request->file;
                $dir = storage_path('app/private').'/uploads/clients/'.$folder;

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                file_put_contents($dir."/".$filename, file_get_contents($file));
            } else {
                $folder = Session::get('activeClient');

                $filename = $request->filename;
                $file = $request->file;
                $dir = storage_path('app/private').'/uploads/clients/'.$folder.'/temp';

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                if(!file_exists($dir."/temp")){
                    mkdir($dir.'/temp', 0777, true);
                }

                file_put_contents($dir."/temp/".$filename, file_get_contents($file));
            }
        }
    }

    /**
     * Delete a uploaded temp job attachment file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTempJobAttachments(Request $request)
    {
        if ($request->isMethod('post')) {
            $filename = $request->filename;
            if(!Auth::check()) {
                $folder = Session::get('uId');
                $dir = storage_path('app/private').'/uploads/clients/'.$folder;
                if(file_exists($dir."/".$filename)){
                    unlink($dir."/".$filename);
                }
            } else {
                $folder = Session::get('activeClient');
                $dir = storage_path('app/private').'/uploads/clients/'.$folder;
                if(file_exists($dir."/temp/".$filename)){
                    unlink($dir."/temp/".$filename);
                }
            }


        }
    }

    /**
     * Rename temp folder with job attachment files to the id of the created job folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveTempFiles($job_id)
    {
        if(Session::has("jobSession")) {
            $folder = Session::get('uId');
            $dir = storage_path('app/private').'/uploads/clients/'.$folder;

            if (!file_exists(storage_path('app/private').'/uploads/clients/'.Session::get('activeClient'))) {
                mkdir(storage_path('app/private').'/uploads/clients/'.Session::get('activeClient'), 0777, true);
            }

            if(File::move($dir, storage_path('app/private').'/uploads/clients/'.Session::get('activeClient').'/'.$job_id)){
                return "Dir verschoben";
            } else {
                return "Dir fail";
            }
        } else {
            $client_id = Session::get('activeClient');
            $dir = storage_path('app/private').'/uploads/clients/'.$client_id.'/temp';

            if(File::move($dir, storage_path('app/private').'/uploads/clients/'.$client_id.'/'.$job_id)){
                return "Dir verschoben";
            } else {
                return "Dir fail";
            }
        }
    }

    /**
     * Get Subcategories with prices of the category and give it back.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubcategoryPrices(Request  $request)
    {
        $subcategories = Subcategory::select('subcategories.id', 'subcategories.cost')
            ->where('subcategories.categories_fk', $request["categories_fk"])
            ->get();

        $output = array();

        if(count($subcategories) !== 0){
            foreach($subcategories as $subcategory){
                $output[md5($subcategory->id)] = number_format((float)$subcategory->cost, 2, '.', '');
            }
        }

        return $output;
    }

    /**
     * Get Subcategories of the category and give it back.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSpecificationPrices(Request  $request)
    {
        $specifications = Specification::select('specifications.id','specifications.name','specifications.cost')
            ->where('specifications.subcategories_fk', $request["subcategories_fk"])
            ->get();

        $output = array();

        if(count($specifications) !== 0){
            foreach($specifications as $specification){
                $specification_id = HomeController::encrypt_decrypt($specification->id,"encrypt");
                $output[$specification_id]["price"] = number_format((float)$specification->cost, 2, '.', '');
                $output[$specification_id]["name"] = $specification->name;
            }
        }

        return $output;
    }

    /**
     * Get Specifications of the subcategory and give it back.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSpecifications(Request  $request)
    {

        $subcategories = Specification::select('id', 'name', 'icon', 'cost','contact')
        ->where('specifications.subcategories_fk', $request["subcategories_fk"])
        ->orderBy('specifications.name')
        ->get();

        $output = "";
        if(count($subcategories) !== 0){
            foreach($subcategories as $subcategory){
                $specification_id = HomeController::encrypt_decrypt($subcategory->id,"encrypt");
                if($subcategory->contact == 1){
                    $output .= '<li class="categoryBoxItem ui-state-highlight contact" data-content="'.$specification_id.'">
                        <img class="contact" src="/images/icons/info.png" title="'.__("job.request_hovertext").'" />
                        <img src="/images/icons/subcategory/placeholder.png" />
                        <span>'.$subcategory->name.'</span>
                        </li>';
                } else {
                    $output .= '<li class="categoryBoxItem ui-state-highlight" data-content="'.$specification_id.'">
                        <img src="/images/icons/subcategory/placeholder.png" />
                        <span>'.$subcategory->name.'</span>
                        </li>';
                }


            }
        } else {
            $output .= '<p class="not-draggable mb-0">'.__("main.no_entries").'</p>';
        }

        return $output;
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
                'name' => 'required|min:10|max:255|regex:/^([0-9a-zA-ZäöüÄÖÜ\- ])+$/|unique:jobs,name,NULL,clients_fk,clients_fk,'.Session::get('activeClient'), /* einigartiger Job Name pro Client */
                'description' => 'nullable',
                'location' => 'nullable',
                'street' => 'nullable|required_if:location,=,true|min:3|max:50|regex:/^[\pL\pM\pN\s]+$/u',
                'plz' => 'nullable|required_if:location,=,true|max:9999|integer',
                'categories_fk' => 'required',
                'subcategories_fk' => 'required',
                'specifications_fk' => 'nullable',
                'complexities_fk' => 'required',
                'cost' => 'required|between:0,99999999.99',
                //'payment_types_fk' => 'required',
                'end' => 'required| after:' . date('d-m-Y'),
                'eventdate' => 'nullable| after:' . date('d-m-Y'). '|before:end',
            ]
        );

        // Location
        if($request['street'] == "" AND $request['plz'] == ""){
            $location = 0;
        } else {
            $location = 1;
        }

        // Spezifikationen
        if($request['specifications_fk'] == "" OR $request['specifications_fk'] === null){
            $specifications = 0;
        } else {
            $specifications = 1;
        }

        // Skills
        /*
        if($request['skills'] !== ""){
            $skills = 1;
        } else {
            $skills = 0;
        }*/

        // unangemeldet -> Session speichern
        // angemeldet -> Eintrag in DB
        if(!Auth::check()) {

            $jobSession = array(
              "name" => $request['name'],
              "description" => $request['description'],
              "location" => $request['location'],
              "street" => $request['street'],
              "plz" => $request['plz'],
              "specifications" => $specifications,
              //"skills" => $skills,
              //"skills_fk" => $request['skills'],
              "subcategories_fk" => $request['subcategories_fk'],
              "categories_fk" => $request['categories_fk'],
              "complexities_fk" => $request['complexities_fk'],
              "cost" => $request['cost'],
              //"payment_types_fk" => $request['payment_types_fk'],
             'status_fk' => 1,
             'attachments' => $request['attachments'],
             'eventdate' => $request['eventdate'],
             'end' => $request['end'],
             'specifications_fk' => $request['specifications_fk']);

            Session::put("jobSession", $jobSession);
        } else {
            $job = new Job(
                [
                    'clients_fk' => Session::get('activeClient'),
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'location' => $location,
                    'street' => $request['street'],
                    'plz' => $request['plz'],
                    'specifications' => $specifications,
                    //'skills' => $skills,
                    //'skills_fk' => $request['skills'],
                    'subcategories_fk' => $request['subcategories_fk'],
                    'categories_fk' => $request['categories_fk'],
                    'complexities_fk' => $request['complexities_fk'],
                    'cost' => $request['cost'],
                    //'payment_types_fk' => $request['payment_types_fk'],
                    'status_fk' => 1,
                    'attachments' => $request['attachments'],
                    'eventdate' => $request['eventdate'],
                    'end' => $request['end'],
                ]);

            $job->save();
            $job_id = $job->id;

            // Spezifikationen iterieren und in DB schreiben
            if(!is_null($request['specifications_fk'])){
                $specifications = explode(",", $request['specifications_fk']);

                foreach($specifications as $specification){
                    $specification = HomeController::encrypt_decrypt($specification,"decrypt");

                    $job_specifications = new job_specification(
                        [
                            'jobs_id' => $job_id,
                            'specifications_id' => $specification,
                        ]);
                    $job_specifications->save();
                }
            }

            // Skills dem Job hinzufügen
            /*
            if($request['skills'] !== ""){

                // Skills iterieren und in DB schreiben
                foreach($request['skills'] as $skill){
                    $skill = HomeController::encrypt_decrypt($skill,"decrypt");
                    $creator_skill = new Job_Skills(
                        [
                            'jobs_id' => $job_id,
                            'skills_id' => $skill,
                        ]);
                    $creator_skill->save();
                }
            }*/

            // Bei Anhang, den /temp Ordner zur Job ID umwandeln
            if($request['attachments']){
                $this->moveTempFiles($job_id);
            }

            // Activity Table Eintrag
            $jobName = $request['name'];
            $link = "/ycity/creator/search?job=".HomeController::encrypt_decrypt($job_id,"encrypt");
            $title = 'Auftrag "'.$jobName.'" erfolgreich erstellt';
            $timestamp = date('Y-m-d H:i:s');

            $values = array('user_id' => Auth::id(),'type' => 'job','title' => $title, 'message' => __('main.search_creator'), 'link' => $link, 'time' => $timestamp);
            Activity::insert($values);
        }
    }

    public function storeJobSession($jobSession){
        // Spezifikationen
        if($jobSession['specifications_fk'] == "" OR $jobSession['specifications_fk'] === null){
            $specifications = 0;
        } else {
            $specifications = 1;
        }

        // Skills
        /*
        if($jobSession['skills'] !== ""){
            $skills = 1;
        } else {
            $skills = 0;
        }*/

        $job = new Job(
            [
                'clients_fk' => Session::get('activeClient'),
                'name' => $jobSession['name'],
                'description' => $jobSession['description'],
                'location' => $jobSession['location'],
                'street' => $jobSession['street'],
                'plz' => $jobSession['plz'],
                'specifications' => $specifications,
                //'skills' => $skills,
                'categories_fk' => $jobSession['categories_fk'],
                'subcategories_fk' => $jobSession['subcategories_fk'],
                'complexities_fk' => $jobSession['complexities_fk'],
                'cost' => $jobSession['cost'],
                //'payment_types_fk' => $jobSession['payment_types_fk'],
                'status_fk' => 1,
                'attachments' => $jobSession['attachments'],
                'eventdate' => $jobSession['eventdate'],
                'end' => $jobSession['end'],
            ]);

        $job->save();
        $job_id = $job->id;
        // Spezifikationen iterieren und in DB schreiben
        if($specifications){
            $specifications = explode(",", $jobSession['specifications_fk']);

            foreach($specifications as $specification){
                $specification = HomeController::encrypt_decrypt($specification,"decrypt");

                $job_specifications = new job_specification(
                    [
                        'jobs_id' => $job_id,
                        'specifications_id' => $specification,
                    ]);
                $job_specifications->save();
            }
        }

        // Skills dem Job hinzufügen
        /*
        if($skills){

            // Skills iterieren und in DB schreiben
            foreach($jobSession['skills_fk'] as $skill){
                $skill = HomeController::encrypt_decrypt($skill,"decrypt");
                $creator_skill = new Job_Skills(
                    [
                        'jobs_id' => $job_id,
                        'skills_id' => $skill,
                    ]);
                $creator_skill->save();
            }
        }*/

        // Bei Anhang, den /temp Ordner zur Job ID umwandeln
        if($jobSession['attachments']){
            $this->moveTempFiles($job_id);
        }

        // Activity Table Eintrag
        $jobName = $jobSession['name'];
        $link = "/ycity/creator/search?job=".HomeController::encrypt_decrypt($job_id,"encrypt");
        $title = 'Auftrag "'.$jobName.'" erfolgreich erstellt';
        $timestamp = date('Y-m-d H:i:s');

        $values = array('user_id' => Auth::id(),'type' => 'job','title' => $title, 'message' => __('main.search_creator'), 'link' => $link, 'time' => $timestamp);
        Activity::insert($values);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $job_id = $job->id;
        $job = Job::select(
            'jobs.id as id',
            'jobs.clients_fk as client',
            'jobs.name as title',
            'jobs.attachments',
            'jobs.description',
            'jobs.location',
            'jobs.street',
            'jobs.plz',
            'plz.place',
            'plz.canton_short',
            'jobs.specifications',
            'jobs.skills',
            'jobs.cost',
            'jobs.creators_fk',
            'jobs.end',
            'jobs.eventdate',
            'jobs.status_fk',
            'users.firstname',
            'users.lastname',
            'clients.name as client_name',
            'clients.website as client_website',
            'payment_types.name as payment_types',
            'categories.name as categories',
            'subcategories.name as subcategories',
            'complexities.name as complexities',
            'status.name as status')
            ->leftJoin('creators', 'creators.id', '=', 'jobs.creators_fk')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->leftJoin('plz', 'plz.plz', '=', 'jobs.plz')
            ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
            ->leftJoin('status', 'status.id', '=', 'jobs.status_fk')
            ->leftJoin('complexities', 'complexities.id', '=', 'jobs.complexities_fk')
            ->leftJoin('categories', 'categories.id', '=', 'jobs.categories_fk')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'jobs.subcategories_fk')
            ->leftJoin('payment_types', 'payment_types.id', '=', 'jobs.payment_types_fk')
            ->where('jobs.id', '=', $job_id)
            ->orderBy('jobs.name')
            ->first();

        $user_id = Auth::id();

        // Spezifikationen herauslesen
        if($job->specifications){
            $specifications = Job_Specification::select("specifications.name")->leftJoin('specifications', 'specifications.id', '=', 'job_specifications.specifications_id')->where("jobs_id", $job_id)->get();
        } else {
            $specifications = array();
        }

        // Skills herauslesen
        /*
        if($job->skills){
            $skills = Job_skills::select("skills.name")->leftJoin('skills', 'skills.id', '=', 'job_skills.skills_id')->where("jobs_id", $job_id)->orderBy("skills.name")->get();
        } else {
            $skills = array();
        }*/

        // Währungsformat
        if(fmod($job->cost, 1) !== 0.00){
            $job->cost = number_format($job->cost, 2, '.', '\'');
        } else {
            $job->cost = number_format($job->cost, 0, '.', '\'');
        }

        // read out attached files, if upload for job is true
        $attached_files = array();
        if($job->attachments == 1){
            $path = storage_path('app/private/uploads/clients/'.$job->client.'/'.$job_id.'/');
            $files = File::allFiles($path);

            $i = 0;
            foreach($files as $file) {
                $filename = $file->getFilename();
                $extension = $file->getExtension();
                $path = "/uploads/clients/".$job->client."/".$job_id;

                $attached_files[$i]["filename"] = $filename;
                $attached_files[$i]["extension"] = $extension;
                $attached_files[$i]["src"] = $path;

                $i++;
            }
        }


        $activeJob = $job->id; // für meta Tag
        $creator_id = Creator::where("users_fk", $user_id)->value("id");
        $applied = Applicant::select('jobs_id', 'creators_id')->where("creators_id", $creator_id)->where("jobs_id", $job_id)->get("creators_id")->count();

        $applicants = Applicant::select('users.firstname', 'users.lastname', 'creators.id as creators_id', 'users.id as users_id', 'users.image', 'applicants.text')
            ->leftJoin('creators', 'creators.id', '=', 'applicants.creators_id')
            ->leftJoin('users', 'users.id', '=', 'creators.users_fk')->where("applicants.jobs_id", $job_id)->get();

        return view("job.show")->with([
            'map' => $job->location, // um Body onload Funktion auszuführen
            'job' => $job,
            'specifications' => $specifications,
            //'skills' => $skills,
            'applied' => $applied,
            'creator_id' => $creator_id,
            'applicants' => $applicants,
            'attached_files' => $attached_files,
            'activeJob' => $activeJob,
        ]);
    }


    // Seite "Aufträge suchen"
    public function searchJobs()
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

        $status = Status::select('id', 'name')
            ->where("id", "!=", 2)
            ->orderBy('name')
            ->get();

        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();

        $subcategories = Subcategory::select('id', 'name', 'categories_fk')
            ->orderBy('name')
            ->get();

        // Jobs für Jobsuche
        $jobs = Job::select(DB::raw('GROUP_CONCAT( skills.name SEPARATOR ", ") as skills'), 'jobs.id',DB::raw('max(jobs.clients_fk) as client'), DB::raw('max(subcategories.name) as subcategories'), DB::raw('max(jobs.name) as name'), DB::raw('max(jobs.attachments) as attachments'),DB::raw('max(jobs.description) as description'),DB::raw('max(jobs.specifications) as specifications'), DB::raw('max(jobs.cost) as cost'),  DB::raw('max(jobs.end) as end'), DB::raw('max(payment_types.name) as payment_type'),DB::raw('max(categories.name) as categories'), DB::raw('max(complexities.name) as complexities'), DB::raw('max(status.name) as status'))
            ->leftJoin('job_skills', 'job_skills.jobs_id', '=', 'jobs.id')
            ->leftJoin('skills', 'skills.id', '=', 'job_skills.skills_id')
            ->leftJoin('status', 'status.id', '=', 'jobs.status_fk')
            ->leftJoin('complexities', 'complexities.id', '=', 'jobs.complexities_fk')
            ->leftJoin('categories', 'categories.id', '=', 'jobs.categories_fk')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'jobs.subcategories_fk')
            ->leftJoin('payment_types', 'payment_types.id', '=', 'jobs.payment_types_fk')
            ->where([
                ['jobs.status_fk', '=', 1],
                ['jobs.end', '>=',  DB::raw('curdate()')]
            ])
            ->orderBy('jobs.end', 'asc')
            ->orderBy('jobs.name', 'asc')
            ->groupBy('jobs.id')
            ->get();


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

        return view("job.searchJobs")->with([
            'jobs' => $jobs,
            'applicants' => $applicants,
            'status' => $status,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'jobsApplied' => $jobsApplied,
            'jobsActive' => $jobsActive,
            'jobsDone' => $jobsDone,
            'jobsExpired' => $jobsExpired,
            'projects' => $projects,
            'applicantsImages' => $applicantsImages
        ]);
    }

    /**
     * Send apply for a job.
     *
     * @return \Illuminate\Http\Response
     */
    public function applyToJob(Request $request)
    {
        $this->validate($request,
            [
                'apply_text' => 'required|min:10',
                'job' => 'required',
            ]
        );

        $job_id = $request["job"];
        $apply_text = $request["apply_text"];
        $user_id = Auth::id();
        $creator = Creator::select("creators.id as creators_id", "users.firstname", "users.lastname")
                    ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
                    ->where("creators.users_fk", $user_id)->first();

        $creators_id = $creator->creators_id;
        $applicant = new Applicant(
            [
                'jobs_id' => $job_id,
                'creators_id' => $creators_id,
                'text' => $apply_text,
            ]);

        if($applicant->save()){
            // Activity Table: Client Eintrag
            $job = Job::select("users.id as users_id","jobs.name")->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')->leftJoin('users', 'users.id', '=', 'clients.users_fk')->where("jobs.id", $job_id)->first();
            $firstname = $creator->firstname;
            $lastname = $creator->lastname;
            $jobName = $job->name;
            $link = "/ycity/creator/".$creators_id."/".$firstname."-".$lastname;
            $title = $firstname.' hat sich für "'.$jobName.'" beworben';
            $timestamp = date('Y-m-d H:i:s');

            $values = array('user_id' => $job->users_id,'type' => 'job','title' => $title, 'message' => __('main.show_profile'), 'link' => $link, 'time' => $timestamp);
            Activity::insert($values);

            // Client Notifikation: Bewerber
            broadcast(new NewNotification($values));

            // Activity Table: Creator Eintrag
            $link = "/ycity/job/".$job_id."/".urlencode($jobName);
            $title = 'Du hast dich für "'.$jobName.'" beworben';
            $values = array('user_id' => Auth::id(),'type' => 'job','title' => $title, 'message' => __('main.show_job'), 'link' => $link, 'seen' => 1, 'time' => $timestamp);
            Activity::insert($values);


            return true;
        } else {
            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        // Berechtigung prüfen, Job zu bearbeiten
        if(HomeController::checkIfUsersJob($job->id)) {
            $skills = Skill::select('id', 'name')
                ->orderBy('name')
                ->get();

            $categories = \DB::table('categories')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            $complexities = \DB::table('complexities')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            $payment_types = \DB::table('payment_types')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            $status = \DB::table('status')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();


            // Skills herauslesen
            $activeSkills = Job_skills::select("skills.id")
                ->leftJoin('skills', 'skills.id', '=', 'job_skills.skills_id')
                ->leftJoin('jobs', 'jobs.id', '=', 'job_skills.jobs_id')
                ->where("jobs_id", $job->id)
                ->get()->keyBy('id')->toArray();

            $client_id = Session::get('activeClient'); // Session mit aktivem Client
            $client = DB::table('clients')->where('id', $client_id)->pluck('name');
            $client_name = $client[0];
            $activeJob = $job->id; // für meta Tag

            return view("job.edit")->with([
                'skills' => $skills,
                'activeSkills' => $activeSkills,
                'client' => $client_name,
                'categories' => $categories,
                'complexities' => $complexities,
                'payment_types' => $payment_types,
                'status' => $status,
                'activeJob' => $activeJob,
                'job' => $job,
            ]);
        } else {
            return view("errors.404");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $this->validate($request,
            [
                'name' => 'required|min:10|max:255|regex:/^([0-9a-zA-ZäöüÄÖÜ\- ])+$/',
                'description' => 'nullable',
                'complexities_fk' => 'required',
                //'payment_types_fk' => 'required',
                'status_fk' => 'required',
                'end' => 'required| after:' . date('d-m-Y'),
            ]
        );

        $job_id = $job->id;

        // alte Skills löschen
        $job_skills=Job_Skills::where("jobs_id", $job_id)->delete();

        // alter Wert von Skills war auf 1, das heisst Skills existierten einmal
        if(count($request['skills']) !== 0){
            // neue Skills iterieren und in DB schreiben
            foreach($request['skills'] as $skill){
                $skill = HomeController::encrypt_decrypt($skill,"decrypt");
                $job_skill = new Job_Skills(
                    [
                        'jobs_id' => $job_id,
                        'skills_id' => $skill,
                    ]);
                $job_skill->save();
            }
            $skills = 1;
        } else {
            $skills = 0;
        }


        $job->update(
            [
                'name' => $request['name'],
                'description' => $request['description'],
                'complexities_fk' => $request['complexities_fk'],
                'skills' => $skills,
                //'payment_types_fk' => $request['payment_types_fk'],
                'status_fk' => $request['status_fk'],
                'end' => $request['end'],
            ]);
        if($job->save()){
            $timestamp = date('Y-m-d H:i:s');
            $jobName = $request['name'];
            $link = "/ycity/job/".$job_id."/".urlencode($jobName);

            if($job->creators_fk !== null){
                $title = 'Aktiver Auftrag "'.$jobName.'" wurde bearbeitet';

                // Activity Table: Client Eintrag
                $job = Job::select("users.firstname", "users.lastname", "users.id as users_id")->leftJoin('creators', 'creators.id', '=', 'jobs.creators_fk')->leftJoin('users', 'users.id', '=', 'creators.users_fk')->where("jobs.id", $job_id)->first();
                $firstname = $job->firstname;
                $lastname = $job->lastname;

                $values = array('user_id' => $job->users_id,'type' => 'job','title' => $title, 'message' => __('main.show_job'), 'link' => $link, 'time' => $timestamp);
                Activity::insert($values);

                // aktiver Creator Notifikation: Job bearbeitet
                broadcast(new NewNotification($values));
            } else {
                $title = 'Beworbener Auftrag "'.$jobName.'" wurde bearbeitet';
                $applicants = Applicant::select('users.firstname', 'users.lastname','users.id as users_id')
                ->leftJoin('creators', 'creators.id', '=', 'applicants.creators_id')
                ->leftJoin('users', 'users.id', '=', 'creators.users_fk')->where("applicants.jobs_id", $job_id)->get();
                if($applicants->count() !== 0){
                    foreach ($applicants as $applicant){
                        $values = array('user_id' => $applicant->users_id,'type' => 'job','title' => $title, 'message' => __('main.show_job'), 'link' => $link, 'time' => $timestamp);
                        Activity::insert($values);
                        // beworbene Creator Notifikation: Job bearbeitet
                        broadcast(new NewNotification($values));
                    }


                }
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $jobName = $job->name;

        if($job->attachments == 1){
            $client_id = Session::get('activeClient');
            File::deleteDirectory(storage_path('app\private').'\\uploads\\clients\\'.$client_id.'\\'.$job->id);
        }
        $job->delete();
        return $this->index()->with([
            'success' => $jobName.' erfolgreich gelöscht!'
        ]);
    }

    /**
     * Upload the final draft.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finalDraftUpload(Request $request){
        if($request->has('file')) {
            $file = $request->file('file');
            $job = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->storeAs("private/uploads/finals/" . $request->input('job'), "final.zip");

            $job = Job::select("users.id as users_id","users.firstname","jobs.name")->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')->leftJoin('users', 'users.id', '=', 'clients.users_fk')->where("jobs.id", $request->input('job'))->first();
            $jobName = $job->name;
            $firstname = $job->firstname;
            $link = "/ycity/job/".$request->input('job')."/".urlencode($jobName);
            $title = $firstname.' hat die finale Version für "'.$jobName.'" hochgeladen';
            $timestamp = date('Y-m-d H:i:s');
            $values = array('user_id' => $job->users_id, 'type' => 'file','title' => $title, 'message' => 'Job anzeigen', 'link' => $link, 'time' => $timestamp);
            broadcast(new NewNotification($values));
            Activity::insert($values);
        }
    }

    /**
     * Mark the job as done.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request)
    {

        $job_id = HomeController::encrypt_decrypt($request["job_id"],"decrypt");

        // Status ändern
        Job::where("id", $job_id)->update(['status_fk' => 3]);

        // Creator benachrichtigen
        $job = Job::select(
            "users.id as users_id",
            "jobs.name",
            "plz.place as creator_place",
            "plz.canton_short as creator_canton_short",
            "creators.plz as creator_plz",
            "creators.street as creator_street",
            "users.email",
            "users.firstname as creator_firstname",
            "users.lastname as creator_lastname")->leftJoin('creators', 'creators.id', '=', 'jobs.creators_fk')->leftJoin('users', 'users.id', '=', 'creators.users_fk')
            ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')->where("jobs.id", $job_id)->first();

        $jobName = $job->name;
        $creatorFirstname = $job->firstname;
        $link = "/ycity/job/".$job_id."/".urlencode($jobName);
        $title = 'Der Auftrag "'.$jobName.'" wurde erfolgreich abgeschlossen.';
        $timestamp = date('Y-m-d H:i:s');
        $values = array('user_id' => $job->users_id, 'type' => 'job','title' => $title, 'message' => __('main.show_job'), 'link' => $link, 'time' => $timestamp);
        broadcast(new NewNotification($values));
        Activity::insert($values);

        $values = array('user_id' => Auth::id(), 'type' => 'job','title' => $title, 'message' => __('main.show_job'), 'link' => $link, 'time' => $timestamp);
        Activity::insert($values);

        $data = Job::select(
            "jobs.end as job_end",
            "jobs.cost as job_cost",
            "clients.name as client_name",
            "clients.street as client_street",
            "clients.plz as client_plz",
            "plz.place as client_place",
            "plz.canton_short as client_canton_short",
            "users.firstname as client_firstname",
            "users.lastname as client_lastname")
            ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
            ->leftJoin('users', 'users.id', '=', 'clients.users_fk')
            ->leftJoin('plz', 'plz.plz', '=', 'clients.plz')
            ->where("jobs.id", $job_id)->first();


        // Email Daten aufbereiten
        $emailData = [
            'creator_firstname' => $job->creator_firstname,
            'creator_lastname' => $job->creator_lastname,
            'creator_street' => $job->creator_street,
            'creator_plz' => $job->creator_plz,
            'creator_place' => $job->creator_place,
            'creator_canton_short' => $job->creator_canton_short,
            'job_name' => $jobName,
            'job_cost' => $data->job_cost,
            'job_end' => $data->job_end,
            'client_name' => $data->client_name,
            'client_firstname' => $data->client_firstname,
            'client_lastname' => $data->client_lastname,
            'client_street' => $data->client_street,
            'client_plz' => $data->client_plz,
            'client_place' => $data->client_place,
            'client_canton_short' => $data->client_canton_short,
        ];

        // Mail an Creator mit Rechnung auslösen
        $defaultMail = env('MAIL_FROM_ADDRESS', 'hi@ycity.ch');
        Mail::to($defaultMail)->send(new RechnungsMail($emailData));

        return true;
    }

    /**
     * Jobs auf Text, Status und Kategorie filtern und zurückgeben.
     */
    public function getJobs(Request $request)
    {
        $jobs = Job::orderBy("name")->get();
        $matchThese = array();

        // Status = offen
        array_push($matchThese, array('jobs.status_fk', '=', 1));

        // Datum nicht abgeloffen
        array_push($matchThese, array('jobs.end', '>=', DB::raw('curdate()')));

        // Nach Suchbegriff filtern, wenn angegeben
        if ($request["term"] !== null) {
            array_push($matchThese, array('jobs.name', 'like', '%'.$request["term"].'%'));
        }

        // Nach Kategorie filtern, wenn ausgewählt
        if($request["category"] !== null){
            $category_id = HomeController::encrypt_decrypt($request["category"],"decrypt");
            array_push($matchThese, array('jobs.categories_fk', '=', $category_id));
        }

        // Nach Subkategorie filtern, wenn ausgewählt
        if ($request["subcategory"] !== null) {
            $subcategory_id = HomeController::encrypt_decrypt($request["subcategory"],"decrypt");
            array_push($matchThese, array('jobs.subcategories_fk', '=', $subcategory_id));
        }

        // Nach Datum, wenn angegeben
        if ($request["date"] !== null) {
            array_push($matchThese, array('jobs.end', '<=',  $request["date"]));
        }

        $jobs = Job::select(DB::raw('GROUP_CONCAT( skills.name SEPARATOR ", ") as skills'), 'jobs.id',DB::raw('max(jobs.clients_fk) as client'), DB::raw('max(subcategories.name) as subcategories'), DB::raw('max(jobs.name) as name'), DB::raw('max(jobs.attachments) as attachments'),DB::raw('max(jobs.description) as description'),DB::raw('max(jobs.specifications) as specifications'), DB::raw('max(jobs.cost) as cost'),  DB::raw('max(jobs.end) as end'), DB::raw('max(payment_types.name) as payment_type'),DB::raw('max(categories.name) as categories'), DB::raw('max(complexities.name) as complexities'), DB::raw('max(status.name) as status'))
            ->leftJoin('job_skills', 'job_skills.jobs_id', '=', 'jobs.id')
            ->leftJoin('skills', 'skills.id', '=', 'job_skills.skills_id')
            ->leftJoin('status', 'status.id', '=', 'jobs.status_fk')
            ->leftJoin('complexities', 'complexities.id', '=', 'jobs.complexities_fk')
            ->leftJoin('categories', 'categories.id', '=', 'jobs.categories_fk')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'jobs.subcategories_fk')
            ->leftJoin('payment_types', 'payment_types.id', '=', 'jobs.payment_types_fk')
            ->where($matchThese)
            ->orderBy('jobs.end', 'asc')
            ->orderBy('jobs.name', 'asc')
            ->groupBy('jobs.id')
            ->get();

        $applicants = Applicant::select(DB::raw('count(applicants.creators_id) as applicants'), 'jobs.id')
            ->rightJoin('jobs', 'jobs.id', '=', 'applicants.jobs_id')
            ->groupBy('jobs.id')
            ->get()
            ->keyBy('id')->toArray();

        $output = "";
        if(count($jobs) !== 0){
            $output .= '<div class="row m-0"><div class="col-md-12 p-0 w-100 ">';
            foreach($jobs as $job){
                $date = $job->end;
                $endDate = date("d.m.Y", strtotime($date));
                if(fmod($job->cost, 1) !== 0.00){
                    $cost = number_format($job->cost, 2, '.', '\'');
                } else {
                    $cost = number_format($job->cost, 0, '.', '\'');
                }

                $job_id = $job->id;
                $applicantCount = $applicants[$job_id]['applicants'];

                if(isset($job->skills)){
                    $skills = " - ".__("main.skills").": ".$job->skills;
                } else {
                    $skills = "";
                }


                $datetime1 = new DateTime();
                $datetime2 = new DateTime($date);
                $difference = $datetime1->diff($datetime2)->format("%a");

                if($difference == 0 AND $difference <= 14){
                    $output .= '<a class="mb-2 d-block" href="/ycity/job/'.$job->id.'/'.urlencode($job->name).'">
                        <div class="jobBox position-relative">
                            <span class="jobName d-block">'.$job->name.'</span>
                            <span class="jobDescription d-block" style="color: #A8A8A8;">'.$applicantCount.' '.__("job.applicants").' - '.$job->categories.' ('.$job->subcategories.') - CHF '.$cost.' - '.__("job.deadline").': '.$endDate.$skills.'</span>
                            <img class="lastMinute" src="/images/icons/last-minute.png" />
                        </div>
                    </a>';
                } else {
                    $output .= '<a class="mb-2 d-block" href="/ycity/job/'.$job->id.'/'.urlencode($job->name).'">
                        <div class="jobBox">
                            <span class="jobName d-block">'.$job->name.'</span>
                            <span class="jobDescription d-block" style="color: #A8A8A8;">'.$applicantCount.' '.__("job.applicants").' - '.$job->categories.' ('.$job->subcategories.') - CHF '.$cost.' - '.__("job.deadline").': '.$endDate.$skills.'</span>
                        </div>
                    </a>';
                }

            }
            $output .= '</div></div>';
        } else {
            $output .= '<p>'.__("job.no_jobs_found").'</p>';
        }

        return $output;
    }
}
