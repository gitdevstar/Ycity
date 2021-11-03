@extends('layouts.app')

@section('headTitle')
    {{$job->title}} - {{ config('app.name', 'Laravel') }}
@endsection


@section('blockLeft')
    @if (Auth::check())
        @if(Session::get('userType') == 'client')
            @if($job->status_fk == 4 OR $job->end < date('Y-m-d'))
                <h2 class="m-0 pb-3 w-100 d-block">{{ __('job.job_expired') }}</h2>
                <p class="d-block w-100">{{ __('job.job_expired_text') }}</p>
            @else
                <div class="container-fluid p-0">
                    @isset($job)
                        @if($job->creators_fk !== null)
                            <h2 class="m-0 pb-3 w-100 d-block">Chat</h2>
                            @include("chat")
                    @else
                            <!-- Bewerber PopUp -->
                            <div id="app2" v-cloak>
                                <client-component inline-template>
                                    <div>
                                        <div class="alert alert-danger" v-if="error">
                                            Fehler beim anheuern des Creators.
                                        </div>
                                        <div v-if="success">
                                            <div class="alert-success alert" >
                                                Creator erfolgreich angeheuert.
                                            </div>
                                            <a href="/ycity/job/{{$job->id}}/{{urlencode($job->title)}}" class="btn-outline d-table">{{ __('job.go_to_chat') }}</a>
                                        </div>

                                        <div id="applicantsContainer" v-if="!success && !error">
                                            <h2 class="m-0 pb-3 w-100 d-block">{{ __('job.applicants') }}</h2>
                                            @inject('homecontroller', 'App\Http\Controllers\HomeController')
                                            @php
                                                $i = 1;
                                            @endphp
                                            @forelse($applicants as $applicant)
                                                <button class="applicant" data-toggle="modal" data-target="#applicantModal{{$i}}">
                                                    <div class="applicantPicture">
                                                        @if($applicant->image !== "avatar.png" )
                                                            <img  class="w-100" src="/images/profiles/{{$applicant->users_id}}/{{$applicant->image}}" />
                                                        @else
                                                            <img class="w-100" src="/images/profiles/{{$applicant->image}}" />
                                                        @endif
                                                    </div>
                                                    <span class="font-weight-bold">{{$applicant->firstname}} {{$applicant->lastname}}</span>
                                                </button>
                                                <!-- Applicant PopUp -->
                                                <div class="modal fade" data-backdrop="false" id="applicantModal{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 id="popupTitle" class="modal-title" id="exampleModalLabel">{{$applicant->firstname}} {{__('job.says')}}:</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{$applicant->text}}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn-outline" data-dismiss="modal">{{ __('main.close') }}</button>
                                                                <a class="btn" href="/ycity/creator/{{$applicant->creators_id}}/{{urlencode($applicant->firstname)}}-{{urlencode($applicant->lastname)}}">{{ __('job.show_profile') }}</a>
                                                                <button @click="hireApplicant('{{$homecontroller::encrypt_decrypt($job->id,"encrypt")}}','{{$homecontroller::encrypt_decrypt($applicant->creators_id,"encrypt")}}')" data-dismiss="modal"  type="submit" class="btn btn-primary">{{ __('main.hire') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $i++;
                                                @endphp
                                            @empty
                                                <p class="d-block w-100">{{ __('job.no_applicants_yet') }}</p>
                                            @endforelse
                                            <a class="btn-outline w-100 mt-3 d-block" href="/ycity/creator/search?job={{$homecontroller::encrypt_decrypt($job->id,"encrypt")}}">{{ __('job.search_creator') }}</a>
                                        </div>
                                    </div>
                                </client-component>
                            @endif
                        </div>
                    </div>
                @endisset
                @empty($job)
                    <p class="h3 pb-3">{{(__('main.oops'))}}</p>
                    <p>{{(__('job.job_not_found'))}}</p>
                    <a class="btn btn-outline-primary btn-sm float-left mr-2" href="javascript:history.back()"><i class="fa fa-arrow-left mr-2"></i>{{(__('main.back'))}}</a>
                    <a class="btn btn-primary btn-sm ml-3 float-left" href="/ycity/client/job/create"><i class="fa fa-plus-circle mr-2"></i><p>{{(__('job.create_new_job'))}}</p></a>
                @endempty
        @endif
    @else
        @if($job->creators_fk === Session::get('creator_id'))
            <h2 class="m-0 pb-3 w-100 d-block">Chat</h2>
            @include("chat")
        @elseif($applied == 0)
            <div class="vertical-center" style="justify-content: normal;">
                <div id="app3" v-cloak>
                    <creator-component inline-template>
                        <div>
                            <div class="alert-success alert" v-if="success" style="display: block" >
                                {{ __('job.application_sent') }}
                            </div>
                            <div class="alert alert-danger" v-if="error">
                                {{ __('job.application_sent_fail') }}
                            </div>
                            <form v-if="!success && !error" class="w-100" style="display:inline-block; min-width: 320px;" @submit.prevent="applyToJob()"  method="post">
                                @csrf
                                <p class="h2 pb-3">{{ __('job.apply') }}</p>
                                <textarea v-on:blur="clearError" :class="hasError('apply_text') ? 'is-invalid' : ''" v-model="formData.apply_text" id="apply_text" placeholder="{{ __('job.apply_placeholder') }}" class="form-control" name="apply_text"></textarea>
                                <span v-if="hasError('apply_text')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['apply_text'][0] }}</strong>
                                    </span>
                                <button type="submit" class="btn btn-primary btn-sm mt-3" >{{ __('job.send') }}</button>

                                <input type="hidden" id="formType" value="apply" />
                                <input type="hidden" id="job" value="{{$job->id}}" />
                            </form>
                        </div>
                    </creator-component>
                </div>
            </div>
        @else
            <p class="pb-1">{{ __('job.already_applied') }}</p>
            <!-- Bewerber PopUp -->
            <div class="container-fluid p-0">
                @isset($job)
                    <div id="applicantsContainer" v-if="!success && !error">
                        <h2 class="m-0 pb-3 w-100 d-block">{{ __('job.other_applicants') }}</h2>
                        @inject('homecontroller', 'App\Http\Controllers\HomeController')
                        @php
                            $i = 0;
                        @endphp
                        @foreach($applicants as $applicant)
                            @if($applicant->creators_id !== Session::get("creator_id"))
                                <a href="/ycity/creator/{{$applicant->creators_id}}?id={{$applicant->creators_id}}&firstname={{$applicant->firstname}}&lastname={{$applicant->lastname}}" class="applicant">
                                    <div class="applicantPicture">
                                        @if($applicant->image !== "avatar.png" )
                                            <img  class="w-100" src="/images/profiles/{{$applicant->users_id}}/{{$applicant->image}}" />
                                        @else
                                            <img class="w-100" src="/images/profiles/{{$applicant->image}}" />
                                        @endif
                                    </div>
                                    <span class="font-weight-bold">{{$applicant->firstname}} {{$applicant->lastname}}</span>
                                </a>
                                @php
                                    $i++;
                                @endphp
                            @endif
                        @endforeach
                        @if($i == 0)
                            <p class="d-block">{{ __('job.no_other_applicants') }}</p>
                        @endif
                    </div>
                @endisset
            </div>
        @endif
    @endif
    @else
        <h2>{{ __('main.not_signed_in') }}</h2>
        <p>{{ __('main.not_signed_in_job') }}</p>
        <a class="btn btn-primary" href="/register">{{ __('user.register') }}</a>
    @endif
@endsection

@section('blockRight')
    <div id="jobInfos">
        @php
            $deadline = date("d.m.Y", strtotime($job->end));
        @endphp
        <h2 class="mb-3">{{$job->title}}</h2>
        <p class="color-grey" style="width: 100%;word-break: break-word">{{$job->categories}} ({{$job->subcategories}})</p>
        @if($job->specifications == 1)
            <div class="mb-2">
                <p><span class="font-weight-bold d-block mb-2">{{ __('job.specifications') }}:</span>
                    @foreach($specifications as $specification)
                        <span class="specificationItem">{{$specification->name}}</span>
                    @endforeach
                </p>
            </div>
        @endif
        <p style="width: 100%;word-break: break-word">{{$job->description}}</p>

        @if($job->eventdate)
            @php
                $eventdate = date("d.m.Y", strtotime($job->eventdate));
            @endphp
            <p class="color-grey"><span class="font-weight-bold">{{ __('job.eventdate') }}</span>: {{$eventdate}}</p>
        @endif

        <p class="color-grey"><span class="font-weight-bold">{{ __('job.deadline') }}</span>: {{$deadline}}</p>
        @if (Auth::check())

            @if(Session::get('userType') == 'client')
                <p class="color-grey"><span class="font-weight-bold">{{ __('job.cost') }}</span>: {{$job->cost}} CHF</p>
            @else
                <p class="color-grey"><span class="font-weight-bold">{{ __('job.earnings') }}</span>: {{$job->cost}} CHF</p>
            @endif

            @if(count($attached_files) > 0)
                <p class="h5 mt-4">{{ __('job.attached_files') }}</p>
                <div id="jobFilesContainer">
                    @foreach($attached_files as $file)
                        @if($file["extension"] == "pdf")
                            <div data-src="{{env("APP_URL")}}{{$file["src"]}}/{{$file["filename"]}}" class="fileContainer showPdf">
                                <div class="attached_file">
                                    <img src="/images/icons/pdf-file.png" />
                                    <span>{{$file["filename"]}}</span>
                                </div>
                            </div>
                        @elseif($file["extension"] == "jpg" OR $file["extension"] == "png" OR $file["extension"] == "jpeg")
                            <div data-src="{{$file["src"]}}/{{$file["filename"]}}" class="fileContainer showImage">
                                <div class="attached_file">
                                    <img src="/images/icons/image-file.png" />
                                    <span>{{$file["filename"]}}</span>
                                </div>
                            </div>
                        @else
                            <div data-src="{{env("APP_URL")}}{{$file["src"]}}/{{$file["filename"]}}" class="fileContainer showFile">
                                <div class="attached_file">
                                    {{$file["extension"]}}
                                    <img src="/images/icons/file.png" />
                                    <span>{{$file["filename"]}}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            @if($job->location == 1)
                <h5 class="mt-4">{{ __('job.location') }}</h5>
                <div id="map" style="border-radius: 6px;height: 230px;width: 100%;"></div>
                <p id="jobLocationAdress" class="mt-2 color-grey">{{$job->street}}, {{$job->plz}} {{$job->place}} {{$job->canton_short}}</p>
                <input type="hidden" id="address" value="{{$job->street}} {{$job->plz}} {{$job->place}}" />
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDo9DxD0o6j4r9giM6dZirzYi0R77Y_UHo"></script>
                <script>
                    var geocoder;
                    var map;
                    function initialize() {
                        geocoder = new google.maps.Geocoder();
                        var latlng = new google.maps.LatLng(46.818188, 8.227512);
                        var mapOptions = {
                            zoom: 9,
                            center: latlng
                        }
                        map = new google.maps.Map(document.getElementById('map'), mapOptions);
                        codeAddress();
                    }

                    function codeAddress() {
                        var address = document.getElementById('address').value;
                        geocoder.geocode( { 'address': address}, function(results, status) {
                            if (status == 'OK') {
                                map.setCenter(results[0].geometry.location);
                                var marker = new google.maps.Marker({
                                    map: map,
                                    position: results[0].geometry.location
                                });
                            } else {
                                document.getElementById('map').textContent = "Ort konnte nicht von Google gefunden werden.";
                                document.getElementById("map").style.height = "auto";
                            }
                        });
                    }
                </script>
            @endif


            @if($job->creators_fk === Session::get('creator_id'))
                    @if(Session::get('userType') == 'creator')
                        @if ($job->status_fk == 3)
                            <div class="mt-3 alert-success alert">
                                Der Auftrag wurde erfolgreich abgeschlossen.
                            </div>
                        @elseif (file_exists(storage_path('app/private/uploads/finals/'.$job->id.'/final.zip')))
                            <p id="finalDraftUploaded">Warte, bis <span class="font-weight-bold">{{$job->client_name}}</span> deine finale Version akzeptiert hat...</p>
                        @elseif($job->status_fk == 2)
                            <div id="finalDraftContainer">
                                <label id="btn-upload" for="finalDraft" class="mt-3 btn-outline d-block">{{ __('job.upload_final_version') }} (.zip)</label>
                                <final-draft-component
                                    :job="{{ $job->id }}">
                                </final-draft-component>
                                <span id="uploadFinalFail" role="alert" class="invalid-feedback text-right py-2"><strong>Nur zip Datei erlaubt</strong></span>
                            </div>
                            <p id="finalDraftUploaded" style="display: none;">Warte, bis <span class="font-weight-bold">{{$job->client_name}}</span> deine finale Version akzeptiert hat...</p>
                        @endif
                    @endif
            @endif
        @else
            <a class="btn btn-outline d-block" href="/register">{{ __('job.apply') }}</a>
        @endif

        @if(Session::get('userType') == 'client')
            @if($job->status_fk !== 3)
                <a class="mt-3 btn-outline d-block" href="/ycity/job/{{$job->id}}/{{urlencode($job->title)}}/edit">{{ __('job.edit_job') }}</a>
            @endif
            @if($job->creators_fk === null)
                <button class="btn d-block mt-2 w-100 deleteButton" data-name="{{$job->title}}" data-id="{{$job->id}}" data-toggle="modal" data-target="#deleteConfirmModal" type="button">{{ __('job.delete_job') }}</button>
                <!-- Delete PopUp -->
                <div id="app3">
                    <job-component inline-template>
                        <div>
                            <div class="modal fade" data-backdrop="false" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 id="popupTitle" class="modal-title" id="exampleModalLabel">{{$job->title}}</h3>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ __('job.delete_job_text') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn-outline" data-dismiss="modal">{{ __('main.close') }}</button>
                                            <button v-on:click="deleteJob({{$job->id}})" data-dismiss="modal"  type="submit" class="btn btn-primary">{{ __('main.delete') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </job-component>
                </div>
            @elseif ($job->status_fk == 3)
                <div class="mt-3 alert-success alert">
                    {{ __('job.finished_job_text') }}
                </div>
            @elseif (file_exists(storage_path('app/private/uploads/finals/'.$job->id.'/final.zip')))
                @inject('homecontroller', 'App\Http\Controllers\HomeController')
                <div id="jobFinishDecision">
                    <a class="mt-3 btn btn-outline d-block" download href="/uploads/finals/{{$job->id}}/final.zip">Finale Version downloaden</a>
                    <div id="app3">
                        <job-component inline-template>
                            <div>
                                <span data-toggle="modal" data-target="#closeJobModal" class="mt-3 btn btn-primary d-block">Auftrag abschliessen</span>
                                <div class="modal fade" data-backdrop="false" id="closeJobModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 id="popupTitle" class="modal-title" id="exampleModalLabel">{{$job->title}}</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{ __('job.finish_job_text') }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-outline" data-dismiss="modal">{{ __('main.close') }}</button>
                                                <button v-on:click="closeJob('{{$homecontroller::encrypt_decrypt($job->id,"encrypt")}}')" data-dismiss="modal"  type="submit" class="btn btn-primary">{{ __('main.finish') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </job-component>
                    </div>
                </div>
                <div id="jobFinishSuccess" class="mt-3 alert-success alert">
                    {{ __('job.congrats_job_text') }}
                </div>
            @endif
        @endif
    </div>
@endsection


