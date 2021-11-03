<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

/* Zugang zu den Files einschränken */
class PrivateController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function jobFiles($client,$job,$file) {
        $path = "private/uploads/clients/{$client}/{$job}/{$file}";

        if(Storage::exists($path)){
            $file = Storage::get($path);
            $type = Storage::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

        return view("errors.404");
    }

    public function chatFiles($job,$file) {
        $path = "private/uploads/chat/{$job}/{$file}";

        // wenn der Job dem User gehört oder der User dem job als Creator dient
        if(Storage::exists($path) AND (HomeController::checkIfUsersJob($job) OR HomeController::checkIfCreatorIsAssignedToJob(Session::get("creator_id"), $job))){
            $file = Storage::get($path);
            $type = Storage::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

        return view("errors.404");
    }

    public function creatorFiles($creator,$file) {
        $path = "private/uploads/creators/{$creator}/{$file}";

        if(Storage::exists($path)){
            $file = Storage::get($path);
            $type = Storage::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

        return view("errors.404");
    }


    public function portfolioFiles($creator,$file) {
        $path = "private/uploads/creators/{$creator}/portfolio/{$file}";

        if(Storage::exists($path)){
            $file = Storage::get($path);
            $type = Storage::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

        return view("errors.404");
    }

    public function finalDraftFile($job) {
        $path = "private/uploads/finals/{$job}/final.zip";

        if(Storage::exists($path) AND HomeController::checkIfUsersJob($job)){
            $file = Storage::get($path);
            $type = Storage::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        }

        return view("errors.404");
    }
}
