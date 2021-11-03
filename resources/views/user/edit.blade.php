@extends('layouts.app')

@section('headTitle')
    {{ __('user.edit_user') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockRight')
    <div class="hideOnMobile">
        @include('global.userNavi')
    </div>
@endsection

@section('blockLeft')
    <div class="moveBefore">
        @php
            $user_id = Auth::id();
            $img = Auth::user()->image;
            if($img !== "avatar.png" ) {
                $path = "/images/profiles/".$user_id."/".$img;
            }   else {
                $path = "/images/profiles/".$img;
            }
        @endphp
        <style>
            .filepond--root {
                max-height: 100px;
                max-width: 100px;
                width: 100%;
                height: 100%;
            }

            .filepond--credits {
                display: none !important;
            }

            .filepond--drop-label label {
                display: none !important;
            }
            .filepond--panel-root {
                background-color: transparent;
                border: 1px solid #DDDDDD;
            }

            #image {
                overflow: visible !important;
                background: url("{{$path}}");
                background-repeat: repeat;
                background-size: auto;
                background-size: cover;
                width: 100px;
                background-repeat: no-repeat;
            }
        </style>
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$user->firstname}} {{$user->lastname}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Bist du dir sicher, dass du diesen Benutzer löschen möchtest? Alle dazugehörigen Firmen und Aufträge werden gelöscht.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">abbrechen</button>
                        <form class="float-right" style="display:inline-block;" action="/ycity/user/delete" method="get">
                            @csrf
                            <button id="deleteUserButton" type="submit" class="btn btn-primary">Löschen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="h2 mb-3">{{ __('user.edit_user') }}</div>
        <div id="app2" v-cloak>
            <edituser-component inline-template>
                <div>
                    <div class="alert alert-danger" v-if="error">
                        Bitte prüfen Sie Ihre Eingaben.
                    </div>
                    <div class="alert-success alert" v-if="success">
                        Benutzer erfolgreich bearbeitet.
                    </div>
                    <form method="PATCH" enctype="multipart/form-data" @submit.prevent="editUser" @change="clearError" @focusout="clearError">
                        @csrf
                        @method('PATCH')
                        <div class="form-row position-relative">
                            <div class="form-group mb-0">
                                <input type="file" class="filepond mb-0" name="filepond" id="image" accept="image/png, image/jpeg, image/gif"/>
                            </div>
                            <div class="form-group">
                                <span onclick="$('.filepond--drop-label').trigger('click');" class="btn-outline vertical-center-always ml-4">{{ __('user.choose_image') }}</span>
                            </div>
                        </div>
                        <div class="form-group mt-1">
                            <span id="image-feedback" class="invalid-feedback" role="alert">
                                <strong>{{__('validation.profile_picture_validation')}}</strong>
                            </span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="firstname">{{ __('user.firstname') }}</label>
                            <input v-model="formData.firstname" :class="hasError('firstname') ? 'is-invalid' : ''" id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') ?? $user->firstname }}"  autocomplete="firstname" autofocus>
                            <span v-if="hasError('firstname')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['firstname'][0] }}</strong>
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="lastname" >{{ __('user.lastname') }}</label>

                            <input v-model="formData.lastname" :class="hasError('lastname') ? 'is-invalid' : ''" id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') ?? $user->lastname  }}"  autocomplete="lastname" autofocus>

                            <span v-if="hasError('lastname')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['lastname'][0] }}</strong>
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="email" >{{ __('user.email') }}</label>
                            <input v-model="formData.email" :class="hasError('email') ? 'is-invalid' : ''" id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? $user->email  }}"  autocomplete="email">

                            <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['email'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="password" >{{ __('user.password_new') }}</label>
                            <div class="position-relative">
                                <input v-model="formData.password" :class="hasError('password') ? 'is-invalid' : ''" id="password" type="password" class="pr-4 form-control" name="password"  autocomplete="new-password">
                                <img id="showPassword" src="/images/icons/eye.png" />
                            </div>
                            <span v-if="hasError('password')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['password'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group mb-0">
                            <button :disabled="hasAnyErrors" type="submit" class="btn btn-primary">
                                {{ __('user.save') }}
                            </button>
                            <!--
                            <button class="btn btn-outline float-right ml-2 p-2" data-toggle="modal" data-target="#deleteConfirmModal" type="button">{{ __('user.delete_user') }}</button>
                       -->
                        </div>
                        <div class="form-group mb-0">
                            <div class="col-md-6 offset-md-4">
                            </div>
                        </div>
                    </form>
                </div>
            </edituser-component>
        </div>
        <script src="{{ asset('js/filepond.js') }}"></script>
    </div>
@endsection
