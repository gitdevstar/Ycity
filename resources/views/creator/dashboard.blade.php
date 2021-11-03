@extends('layouts.app')

@section('headTitle')
    Dashboard - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    <div class="container-fluid p-0">
    @if(Session::get("activated"))
            @if(!$projects)
                <div class="banner banner-pink">
                    <div class="close-banner"></div>
                    <div class="title"><h1>{{__('main.search_project')}}</h1></div>
                    <a class="button" href="/ycity/job/search">{{__('main.search_project')}}</a>
                </div>
            @endif
            @if(!$portfolio)
                <div class="banner banner-green">
                    <div class="close-banner"></div>
                    <div class="title"><h1>{{__('main.create_portfolio')}}</h1></div>
                    <a class="button" href="/ycity/creator/portfolio/edit">{{__('main.getting_started')}}</a>
                </div>
            @endif
            @if($skillsCount == 0)
                <div class="banner banner-orange">
                    <div class="close-banner"></div>
                    <div class="title"><h1>{{__('main.add_skills')}}</h1></div>
                    <a class="button" href="/ycity/creator/skills/edit">{{__('skills.edit_my_skills')}}</a>
                </div>
            @endif
            @if($userImage == "avatar.png")
                <div class="banner banner-blue">
                    <div class="close-banner"></div>
                    <div class="title"><h1>{{__('main.change_profile_image')}}</h1></div>
                    <a class="button" href="/ycity/user/edit">{{ __('user.edit_user') }}</a>
                </div>
            @endif
            @include("global.activities")
            <div id="notifications">
                <notifications-component></notifications-component>
            </div>
    @else
            <p>Dein Profil wurde noch nicht aktiviert. Bitte warte, bis Y-City deine Bewerbung bearbeitet hat.</p>
    @endif
    </div>
@endsection

@section('blockRight')
    <div class="moveBefore">
        @include("creator.projects")
    </div>
@endsection
