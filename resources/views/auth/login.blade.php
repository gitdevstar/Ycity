@extends('layouts.imageLeft')

@section('headTitle')
    {{ __('navi.login') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    <div class="carousel showOnLoad">
        <div><p class="w-100 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ultrices fermentum</p></div>
        <div><p class="w-100 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ultrices fermentum</p></div>
        <div><p class="w-100 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ultrices fermentum</p></div>
    </div>
@endsection

@section('blockRight')
<div class="vertical-center">
    <div class="col-md-10 pl-2">
        <div class="col-sm-12 mb-4 px-0">
            <h2 class="mb-0 ">{{ __('user.login')}}</h2>
        </div>
        <div id="app" v-cloak>
            <login-component inline-template>
                <div>
                    <div class="alert alert-danger" v-if="error">
                        Bitte prüfen Sie Ihre Eingaben.
                    </div>
                    <form method="POST" enctype="multipart/form-data" @submit.prevent="checkUser" @change="clearError" @focusout="clearError">
                        @csrf
                        <div class="form-row m-0">
                            <label for="email" class="px-0 col-md-4 col-form-label">{{ __('user.email') }}</label>
                            <div class="col-md-10 p-0 ">
                                <input v-model="formData.email" id="email" type="email" class="form-control" :class="hasError('email') ? 'is-invalid' : ''" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
                                <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['email'][0] }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-row m-0">
                            <label for="password" class="px-0 col-md-4 col-form-label">{{ __('user.password') }}</label>
                            <div class="col-md-10 p-0  position-relative">
                                <input :class="hasError('password') ? 'is-invalid' : ''" v-model="formData.password" id="password" type="password" class="pr-4 form-control" name="password"  autocomplete="current-password">
                                <img id="showPassword" src="/images/icons/eye.png" />
                                <span v-if="hasError('password')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['password'][0] }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-row my-3 mx-0">
                            <div class="col-md-10">
                                <div class="form-check float-md-left">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('user.remember_me') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <div class="float-md-right">
                                        <a class="btn-link" href="{{ route('password.request') }}">
                                            {{ __('user.password_forgot') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row m-0">
                            <div class="col-md-5 ">
                                <button :disabled="hasAnyErrors" type="submit" class="rounded-pill btn btn-block btn-primary">
                                    {{ __('user.login') }}
                                </button>
                                <a class="link-primary d-table justify-content-center mb-0 mt-4" href="{{ route('register') }}">
                                    {{ __('auth.notRegisteredYet') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </login-component>
        </div>
    </div>
</div>
@endsection
