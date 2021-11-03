<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Activity;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages(Request $request)
    {
        return Message::select("messages.jobs_fk as job", "messages.message","messages.file","messages.type","messages.created_at","users.firstname","users.lastname","users.image","users.id")->where("jobs_fk", $request['jobId'])
            ->leftJoin('users', 'users.id', '=', 'messages.users_fk')
            ->orderBy("messages.id", "asc")
            ->get();
    }

    /**
     * Chat Dateiupload
     *
     * @return Message
     */
    public function chatUpload(Request $request)
    {
        if($request->has('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->storeAs("private/uploads/chat/" . $request->input('job'), $filename);

            // Dateinachricht
            $message = new Message(
                [
                    'file' => $filename,
                    'users_fk' => $request->input('id'),
                    'jobs_fk' => $request->input('job'),
                    'type' => $request->input('type'),
                ]);

            $message->save();

        }
    }

    /**
     * Chat Nachricht
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {

        if($request->input('type') == "message") {
            // Textnachricht
            $message = new Message(
                [
                    'message' => $request->input('message'),
                    'users_fk' => $request->input('id'),
                    'jobs_fk' => $request->input('job'),
                    'type' => $request->input('type'),
                ]);

            $message->save();
        }

        // Notifikation: Creator
        if(Session::get("userType") == "client"){
            $job = Job::select("jobs.name", "creators.users_fk as users_id",)
                ->leftJoin('creators', 'creators.id', '=', 'jobs.creators_fk')
                ->where("jobs.id", $request->input('job'))
                ->first();

            if($request->input('type') == "file") {
                $title = 'Neue Datei vom Client '.Auth::user()->firstname.' erhalten';
            } else {
                $title = 'Neue Nachricht vom Client '.Auth::user()->firstname.' erhalten';
            }
        } else {
            $job = Job::select("jobs.name", "clients.users_fk as users_id",)
                ->leftJoin('clients', 'clients.id', '=', 'jobs.clients_fk')
                ->where("jobs.id", $request->input('job'))
                ->first();
            if($request->input('type') == "file") {
                $title = 'Neue Datei vom Creator '.Auth::user()->firstname.' erhalten';
            } else {
                $title = 'Neue Nachricht vom Creator '.Auth::user()->firstname.' erhalten';
            }
        }

        $uId = $job->users_id;
        $jobName = $job->name;
        $link = "/ycity/job/".$request->input('job')."/".urlencode($jobName);
        $timestamp = date('Y-m-d H:i:s');
        $msgTimestamp = date('d. M y H:i');

        if($request->input('type') == "file") {
            // Datei
            $msg = __('main.show_file');

            $notification = array('user_id' => $uId,
                'type' => 'file',
                'title' => $title,
                'message' => $msg,
                'link' => $link,
                'time' => $msgTimestamp,
                'file' => $request->input('file'),
                'job' => $request->input('job'),
                'firstname' => Auth::user()->firstname,
                'uId' => Auth::user()->id,
                'image' => Auth::user()->image,
                'timestamp' => $timestamp);

            $activityType = "file";
        } else {
            // Nachricht
            $msg = __('main.show_message');

            $notification = array('user_id' => $uId,
                'type' => 'message',
                'title' => $title,
                'message' => $msg,
                'link' => $link,
                'time' => $msgTimestamp,
                'chatMsg' => $request->input('message'),
                'firstname' => Auth::user()->firstname,
                'uId' => Auth::user()->id,
                'image' => Auth::user()->image,
                'timestamp' => $timestamp);

            $activityType = "message";
        }

        // Push Notification durchgeben
        broadcast(new NewNotification($notification));

        // Eintrag in Activity Table, wenn Empfänger nicht eingeloggt
        if(strpos($request->input('onlineUsers'), strval($uId)) !== false) {
            return true;
        } else {
            $values = array('user_id' => $uId,'type' => $activityType,'title' => $title, 'message' => $msg, 'link' => $link, 'time' => $timestamp);
            Activity::insert($values);
            return true;
        }

    }
}
