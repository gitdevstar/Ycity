@extends('layouts.app')

@section('headTitle')
    {{ __('user.user') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
@php
    $user_id = Auth::id();
@endphp
<div id="user-content" class="container-fluid">
    <a class="btn btn-primary btn-sm float-right" href="/ycity/user/edit"><i class="fa fa-edit mr-2"></i>{{ __('user.edit_user') }}</a>
    <div class="d-block clearfix mb-5"></div>
    <p class="h5 pb-3">
        @if(is_dir($_SERVER["DOCUMENT_ROOT"]."/images/profiles/".$user_id) )
            <img id="profilePicture"  class="mr-2" style="width: 30px; height: 30px;" src="/images/profiles/{{Auth::id()}}/{{Auth::user()->image}}" />
        @else
            <img id="profilePicture" class="mr-2"  style="width: 30px; height: 30px;"  src="/images/profiles/{{$user->image}}" />
        @endif
        {{$user->firstname}} {{$user->lastname}}</p>
    <div class=""card-body>
        <p>E-Mail: {{$user->email}}</p>
    </div>
</div>
@endsection

