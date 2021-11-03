@extends('layouts.app')

@section('headTitle')
    Dashboard - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    <div class="container-fluid p-0">
            <div class="banner banner-green">
                <div class="close-banner"></div>
                <div class="title"><h1>{{__('main.create_project')}}</h1></div>
                <a class="button" href="/ycity/job/create">{{__('main.create_project')}}</a>
            </div>
        @include("global.activities")
        <div id="notifications">
            <notifications-component></notifications-component>
        </div>
    </div>
@endsection

@section('blockRight')
    <div class="moveBefore">
        @include("client.projects")
    </div>
@endsection
