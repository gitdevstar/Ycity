    @if(Session::get("activated"))
    <h2 class=" pb-3">{{__('main.your_projects')}}</h2>
    @if(!$projects)
        <img class="w-100 mx-auto d-table" style="max-width:256px; height: auto; " src="/images/icons/projects-empty-state.png" alt="{{__('main.no_projects')}}" />
        <p class="text-center mt-3 "><a class="link-primary" href="/ycity/job/search">{{__('main.search')}}?</a></p>
    @else
        @if(count($jobsApplied) !== 0)
            <p class="h4 mt-2">{{__('main.applied')}}</p>
            <div class="row m-0">
                <div class="col-md-12 p-0 w-100 ">
                    @foreach($jobsApplied as $job)
                        @php
                            $applicantCount = $applicants[$job->id]['applicants'];
                        @endphp
                        @if($applicantCount == 0)
                            <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                                <div class="jobBox container-fluid w-100 m-0">
                                    <span class="jobName">{{$job->name}}</span>
                                    <span class="jobDescription d-block">{{__('job.no_applicants_yet')}}</span>
                                </div>
                            </a>
                        @else
                            <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                                <div class="jobBox container-fluid w-100 m-0">
                                    <div class="row m-0">
                                        <div class="col p-0">
                                            <span class="jobName">{{$job->name}}</span>
                                            <span class="jobDescription d-block">{{__('job.show_applicants')}}</span>
                                        </div>
                                        <div class="col p-0 position-relative">
                                            <div class="profileImages">
                                                @foreach($applicantsImages as $applicantImage)
                                                    @if($applicantImage["job_id"] == $job->id)
                                                        <div class="profileImageOutter">
                                                            @if($applicantImage["users_image"] == "avatar.png")
                                                                <img src="/images/profiles/{{$applicantImage["users_image"]}}" />
                                                            @else
                                                                <img src="/images/profiles/{{$applicantImage["users_id"]}}/{{$applicantImage["users_image"]}}" />
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($jobsActive) !== 0)
            <h2 class="h4 mt-4">{{__('main.active')}}</h2>
            <div class="row m-0">
                <div class="col-md-12 p-0 w-100 ">
                    @foreach($jobsActive as $job)
                        @php
                            $datetime1 = new DateTime();
                            $datetime2 = new DateTime($job->end);
                            $difference = $datetime1->diff($datetime2)->format("%a");
                        @endphp
                        <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                            <div class="jobBox container-fluid w-100 m-0">
                                <span class="jobName">{{$job->name}}</span>
                                <span class="jobDescription d-block">{{__('job.days_left')}}:
                                     @if($difference == 0)
                                        {{__('job.today')}}
                                    @else
                                        {{$difference}}
                                    @endif
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($jobsDone) !== 0)
            <h2 class="h4 mt-4">{{__('main.done')}}</h2>
            <div class="row m-0">
                <div class="col-md-12 p-0 w-100 ">
                    @foreach($jobsDone as $job)
                        <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                            <div class="jobBox container-fluid w-100 m-0">{{$job->name}}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($jobsExpired) !== 0)
            <h2 class="h4 mt-4">{{__('main.expired')}}</h2>
            <div class="row m-0">
                <div class="col-md-12 p-0 w-100 ">
                    @foreach($jobsExpired as $job)
                        <a class="mb-2 d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}">
                            <div class="jobBox container-fluid w-100 m-0">
                                <span class="jobName">{{$job->name}}</span>
                                <span class="jobDescription d-block">{{__('job.everything_done')}}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
@endif
