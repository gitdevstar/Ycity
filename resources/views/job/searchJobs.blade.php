@extends('layouts.app')

@section('headTitle')
    {{__('navi.search_jobs')}} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    @inject('homecontroller', 'App\Http\Controllers\HomeController')
    <div class="container-fluid p-0">
        <p class="h2 pb-3">{{__('navi.search_jobs')}}</p>
        <div id="app2" v-cloak>
            <job-component inline-template>
                <div>
                    <form method="POST" @submit.prevent="getJobs()">
                        @csrf
                        <div class="form-row m-0">
                            <div class="col-md-4 p-0 mb-2 mb-md-0">
                                <label for="jobSearch">{{__('main.search')}}</label>
                                <input class="form-control" v-on:keyup="getJobs('term')" v-model="jobSearch.term" type="text" name="jobSearch"  id="jobSearch" />
                            </div>
                            <div class="col-md-2 p-0 pl-md-2 mb-2 mb-md-0">
                                <label for="jobCategory">{{__('job.category')}}</label>
                                <select v-on:change="getJobs('category')" v-model="jobSearch.category" name="jobCategory" id="jobCategory" class="form-control">
                                    <option value="">{{__('main.all')}}</option>
                                    @foreach($categories AS $category)
                                        @if (old('jobCategory') == $category->id)
                                            <option value="{{$homecontroller::encrypt_decrypt($category->id,"encrypt")}}" selected>{{ $category->name }}</option>
                                        @else
                                            <option value="{{$homecontroller::encrypt_decrypt($category->id,"encrypt")}}">{{ $category->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 p-0 pl-md-2 mb-2 mb-md-0">
                                <label for="jobSubcategory">{{__('job.subcategory')}}</label>
                                <select v-on:change="getJobs('subcategory')" v-model="jobSearch.subcategory" name="jobSubcategory" id="jobSubcategory" class="form-control">
                                    <option value="">{{__('main.all')}}</option>
                                    @foreach($subcategories AS $subcategory)
                                        @if (old('jobSubcategory') == $subcategory->id)
                                            <option data-id="{{$homecontroller::encrypt_decrypt($subcategory->categories_fk,"encrypt")}}" value="{{$homecontroller::encrypt_decrypt($subcategory->id,"encrypt")}}" selected>{{ $subcategory->name }}</option>
                                        @else
                                            <option data-id="{{$homecontroller::encrypt_decrypt($subcategory->categories_fk,"encrypt")}}" value="{{$homecontroller::encrypt_decrypt($subcategory->id,"encrypt")}}">{{ $subcategory->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 p-0 pl-md-2 mb-2 mb-md-0">
                                <label for="date">{{__('job.deadline')}}</label>
                                <input v-on:change="getJobs()" v-model="jobSearch.date" name="date" type="date" class="form-control" id="date" value="{{ old('date')}}" autocomplete="date">
                            </div>
                        </div>
                    </form>
                </div>
            </job-component>
        </div>
        <div id="jobList" class="mt-3" >
            @if(count($jobs) !== 0)
                <div class="row m-0">
                    <div class="col-md-12 p-0 w-100 ">
                        @forelse($jobs as $job)
                            @php
                                $date = $job->end;
                                $endDate = date("d.m.Y", strtotime($date));
                                if(fmod($job->cost, 1) !== 0.00){
                                    $cost = number_format($job->cost, 2, '.', '\'');
                                } else {
                                    $cost = number_format($job->cost, 0, '.', '\'');
                                }
                                $job_id = $job->id;
                                $applicantCount = $applicants[$job_id]['applicants'];

                                $datetime1 = new DateTime();
                                $datetime2 = new DateTime($date);
                                $difference = $datetime1->diff($datetime2)->format("%a");
                            @endphp
                            @if($difference == 0 AND $difference <= 14)
                                <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                                    <div class="jobBox position-relative">
                                        <span class="jobName d-block">{{$job->name}}</span>
                                        <span class="jobDescription d-block" style="color: #A8A8A8;">{{$applicantCount}} {{__("job.applicants")}} - {{$job->categories}} ({{$job->subcategories}}) - CHF {{$cost}} - {{__("job.deadline")}}: {{$endDate}}@isset($job->skills) - {{__("main.skills")}}: {{$job->skills}}@endisset</span>
                                        <img class="lastMinute" src="/images/icons/last-minute.png" />
                                    </div>
                                </a>
                            @else
                                <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                                    <div class="jobBox">
                                        <span class="jobName d-block">{{$job->name}}</span>
                                        <span class="jobDescription d-block" style="color: #A8A8A8;">{{$applicantCount}} {{__("job.applicants")}} - {{$job->categories}} ({{$job->subcategories}}) - CHF {{$cost}} - {{__("job.deadline")}}: {{$endDate}}@isset($job->skills) - {{__("main.skills")}}: {{$job->skills}}@endisset</span>
                                    </div>
                                </a>
                            @endif
                        @empty
                            <p>{{__('job.no_jobs_found')}}</p>
                        @endforelse
                    </div>
                </div>
            @else
                <p class="h5">{{__('job.no_jobs_found')}}</p>
            @endif
        </div>
    </div>
@endsection

@section('blockRight')

    @if (Auth::check())
        @include("creator.projects")
    @endif
@endsection
