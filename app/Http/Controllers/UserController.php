<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Creator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Nette\Utils\Image;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view("user.show", compact('user'));
    }

    public function show(User $user)
    {
        $user = Auth::user();
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user = Auth::user();

        return view('user.edit', compact('user'));
    }

    public function settings(User $user)
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user_id = Auth::id();
        $this->validate($request,
            [
                'firstname' => 'required|min:3|max:50|string',
                'lastname' => 'required|min:3|max:50|string',
                'email' => 'required|unique:users,email,'.$user_id,
                'password' => ['nullable', 'string', Password::min( 8 )->mixedCase()->numbers()->symbols()->uncompromised(),],
            ]);


        if(!$request['image'] AND is_null($request['password'])){
            $data = [
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
            ];
            User::where('id', $user_id)->update($data);
        } else if(!$request['image']) {
            $data = [
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ];
            User::where('id', $user_id)->update($data);
        } else {

            $newProfileImage = Session::get("newProfileImage");
            $oldProfileImage = Session::get("oldProfileImage");

            if($oldProfileImage !== "avatar.png"){
                Storage::delete("/public/images/profiles/".Auth::id()."/".$oldProfileImage);
            }

            $data = [
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'image' => $newProfileImage,
            ];

            User::where('id', $user_id)->update($data);

            return "/images/profiles/".Auth::id()."/".$newProfileImage;
        }
    }

    public function delete(User $user)
    {
        $user_id = Auth::id();
        Auth::logout();
        User::where('id', $user_id)->delete();
        return view('indexClient');
    }

    // Rechnungsoptionen
    public function billingOptions(User $user)
    {
        $user_id = Auth::id();
        if(Session::get("userType") == "creator"){
            // Creator
            $user = Creator::select('users.firstname','users.lastname','creators.street','creators.plz','plz.canton_DE','plz.canton_short','plz.place')
                ->leftJoin('users', 'users.id', '=', 'creators.users_fk')
                ->leftJoin('plz', 'plz.plz', '=', 'creators.plz')
                ->where('creators.users_fk', $user_id)
                ->get()
                ->first();
        } else {
            // Creator
            $user = Client::select('users.firstname','users.lastname','clients.street','clients.plz','plz.canton_DE','plz.canton_short','plz.place')
                ->leftJoin('users', 'users.id', '=', 'clients.users_fk')
                ->leftJoin('plz', 'plz.plz', '=', 'clients.plz')
                ->where('clients.users_fk', $user_id)
                ->get()
                ->first();
        }
        return view("user.billingOptions", compact('user'));
    }

    // Benachrichtigungen
    public function notifications(User $user)
    {
        return view('user.notifications');
    }

    // Profilbild in Session abspeichern
    public function storeProfileImage(Request $request) {
        if($request->hasFile('filepond')){
            Session::forget("newProfileImage");
            Session::forget("oldProfileImage");

            $file = $request->file('filepond');
            $extension = $file->getClientOriginalExtension();
            $imageName = time().'.'.$extension;

            Session::put("newProfileImage", $imageName);
            $file->move(public_path('/images/profiles/'.Auth::id()), $imageName);
            $oldImageName = Auth::user()->image;
            Session::put("oldProfileImage", $oldImageName);

            return "new: ".$imageName." - old: ".$oldImageName;
        } else {
            return "Error";
        }
    }

    // Profilbild löschen
    public function deleteProfileImage(Request $request) {
        $newProfileImage = Session::get("newProfileImage");
        File::delete(public_path('/images/profiles/'.Auth::id().'/'.$newProfileImage));

        Session::forget("newProfileImage");
        Session::forget("oldProfileImage");
    }
}
