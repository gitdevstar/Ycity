@extends('layouts.app')

@section('headTitle')
    {{ __('portfolio.portfolio_of') }} {{$creator->firstname}} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
<div class="container-fluid p-0">
    @if (Auth::check())
        @if($creator->creators_id == Session::get('creator_id'))
            <h2>{{ __('portfolio.my_portfolio') }}</h2>
        @else
            <h2>{{ __('portfolio.portfolio_of') }} {{$creator->firstname}}</h2>
        @endif
        <div class="mt-3" id="portfolioContainer">
            @forelse($images as $image)
                <div class="portfolioItem">
                    <img class="showImage" data-src="/uploads/creators/{{$creator->creators_id."/portfolio/".$image}}" src="/uploads/creators/{{$creator->creators_id."/portfolio/".$image}}" />
                </div>
            @empty
                <p class="d-block w-100">{{__('portfolio.no_entries')}}</p>
                @if(Session::get('userType') == 'creator' AND Session::get("activated"))
                    <a href="/ycity/creator/portfolio/edit" class="btn btn-outline mt-1">{{ __('portfolio.edit_my_portfolio') }}</a>
                @elseif(Session::get('userType') == 'creator' AND !Session::get("activated"))
                    Du kannst dein Portfolio erst anpassen, wenn dein Profil aktiviert wurde.
                @endif
            @endforelse
        </div>
    @else
        <h2>Nicht angemeldet.</h2>
        <p>Du möchtest das Portfolio dieses Creators anschauen? Registriere dich noch heute bei Y-City!</p>
        <a class="btn btn-primary" href="/register">{{ __('user.register') }}</a>
    @endif
</div>
@endsection

@section('blockRight')
    <div class="moveBefore">
        <div id="creatorInfo" class="container-fluid p-0">
            <div class="row m-0">
                <div id="creatorsHead" class="container-fluid p-0">
                    <div class="row m-0">
                        <div class="col-xs-10 col-sm-1 col-md-10 col-lg-2 p-0 mb-2">
                            <div class="creatorsImage">
                                @if($creator->image !== "avatar.png" )
                                    <img src="/images/profiles/{{$creator->users_id}}/{{$creator->image}}" />
                                @else
                                    <img src="/images/profiles/{{$creator->image}}" />
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-10 col-sm-9 col-md-10 col-lg-8 pl-0 pl-sm-2 pl-md-0 pl-lg-2 pr-0 mb-2">
                            <h2 class="mb-1">{{$creator->firstname}}</h2>
                            <p class="color-grey mb-0">{{ __('portfolio.from') }} {{$creator->canton_DE}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-0 mt-2">
                <div class="col-xs-10 p-0">
                    <p>{{$creator->description}}</p>
                </div>
            </div>
            <div class="row m-0">
                <p>
                    <span class="font-weight-bold d-block mb-2">{{ __('portfolio.skills') }}:</span>
                    @forelse($skills as $skill)
                        <span class="skillsItem">{{$skill->name}}</span>
                    @empty
                        <span class="skillsItem">{{ __('main.none') }}</span>
                    @endforelse
                </p>

            </div>
            <!--
            <div class="row m-0">
                <p>
                    <span class="font-weight-bold d-block mb-2">{{ __('portfolio.awards') }}:</span>
                    <span class="skillsItem">{{ __('main.none') }}</span>
                </p>
            </div>
            -->
            @inject('homecontroller', 'App\Http\Controllers\HomeController')
            @if(Session::get('userType') == 'client' AND !is_null($job_hire_id) AND !$homecontroller::checkIfCreatorAppliedToJob($creator->creators_id,$job_hire_id))
                <div id="app2" v-cloak>
                    <client-component inline-template>
                        <div>
                            <div id="sendRequestToCreatorButton" class="row m-0 mb-3">
                                <button v-on:click="sendRequestToCreator('{{$creator->creators_id}}', '{{$job_hire_id}}')" class="btn-outline w-100">{{ __('portfolio.send_request_for_job') }}</button>
                            </div>
                            <div class="alert-success alert" v-if="success">
                                {{ __('portfolio.request_success') }}
                            </div>
                        </div>
                    </client-component>
                </div>
            @endif
            @if (!Auth::check())
                <a class="btn btn-outline d-block" href="/register">{{ __('portfolio.work_with_creator') }}</a>
            @endif
        </div>
    </div>
@endsection

