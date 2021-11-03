@extends('layouts.app')

@section('headTitle')
    {{__('navi.search_jobs')}} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    <div class="container-fluid p-0">
        <p class="h2 pb-3">{{__('creatorlist.search_creator')}}</p>
        @if($job_id == "none")
            <p class="h5">{{__('creatorlist.job_not_found')}}</p>
        @elseif($job_id == "not yours")
            <p class="h5">{{__('creatorlist.job_not_yours')}}</p>
        @else
            <div id="app2" v-cloak>
                <creator-component inline-template>
                    <div>
                        <form method="POST">
                            @csrf
                            <div class="form-row m-0">
                                <div class="col-10 p-0 mb-2 mb-md-0">
                                    <label for="creatorSearch">{{__('main.search')}}</label>
                                    <input placeholder="{{__('creatorlist.search_placeholder')}}" class="form-control" v-on:keyup="getCreators('{{$_GET['job']}}')" v-model="searchTerm" type="text" name="creatorSearch"  id="creatorSearch" />
                                </div>
                            </div>
                        </form>
                    </div>
                </creator-component>
            </div>
            <div id="creatorList" class="mt-3">
                @if(count($creators) !== 0)
                    <div class="row m-0">
                        <div class="col-md-10 p-0 w-100 ">
                            @foreach($creators as $creator)
                                <a class="mb-2 d-block" href="/ycity/creator/{{$creator->creators_id}}/{{urlencode($creator->firstname)}}-{{urlencode($creator->lastname)}}?job={{$job_hash}}">
                                    <div class="creatorBox">
                                        <div class="row m-0">
                                            <div class="col-10 col-sm-2 col-md-1 p-0">
                                                @if(is_dir($_SERVER["DOCUMENT_ROOT"]."/images/profiles/".$creator->users_id) )
                                                    <img class="creatorImage" src="/images/profiles/{{$creator->users_id}}/{{$creator->image}}" />
                                                @else
                                                    <img class="creatorImage" src="/images/profiles/{{$creator->image}}" />
                                                @endif
                                            </div>
                                            <div class="col-10 col-sm-8 col-md-9 p-0 position-relative">
                                                <span class="creatorName">{{$creator->firstname}} {{$creator->lastname}}</span>
                                                <span class="creatorDescription d-block">{{$creator->place}} {{$creator->canton_short}}@isset($creator->skills) - {{__("main.skills")}}: {{$creator->skills}}@endisset</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="h5">{{__('creatorlist.no_creator_found')}}</p>
                @endif
            </div>
        @endif
    </div>
@endsection

@section('blockRight')
    @if (Auth::check() AND Session::get('userType') == 'client')
        @include("client.projects")
    @endif
@endsection
