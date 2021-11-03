@extends('layouts.imageLeft')

@section('headTitle')
    {{ __('navi.register') }} - {{ config('app.name', 'Laravel') }}
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
            <div class="col-sm-12 mb-4">
                <h2 class="mb-0">{{ __('user.register')}}</h2>
            </div>
            <div id="app" v-cloak>
                <register-component inline-template>
                    <div>
                        <div class="alert alert-danger" v-if="error">
                            Bitte prüfen Sie Ihre Eingaben.
                        </div>
                        <form method="POST" enctype="multipart/form-data" @submit.prevent="storeUser" @change="clearError" @focusout="clearError">
                            @csrf
                            <div class="form-row m-0">
                                <label for="firstname" class="px-0 col-md-4 col-form-label">{{ __('user.firstname') }}</label>

                                <div class="col-md-10 p-0">
                                    <input v-model="formData.firstname" id="firstname" type="text" class="form-control" :class="hasError('firstname') ? 'is-invalid' : ''" name="firstname" value="{{ old('firstname') }}"  autocomplete="firstname" autofocus>

                                    <span v-if="hasError('firstname')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['firstname'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row m-0">
                                <label for="lastname" class="px-0 col-md-4 col-form-label">{{ __('user.lastname') }}</label>

                                <div class="col-md-10 p-0">
                                    <input v-model="formData.lastname" id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" :class="hasError('lastname') ? 'is-invalid' : ''" autocomplete="lastname" autofocus>
                                    <span v-if="hasError('lastname')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['lastname'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row m-0">
                                <label for="email" class="px-0 col-md-4 col-form-label">{{ __('user.email') }}</label>

                                <div class="col-md-10 p-0">
                                    <input v-model="formData.email" id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" :class="hasError('email') ? 'is-invalid' : ''" autocomplete="email">
                                    <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['email'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row m-0 mb-4">
                                <label for="password" class="px-0 col-md-4 col-form-label">{{ __('user.password') }}</label>

                                <div class="col-md-10 p-0 position-relative">
                                    <input :class="hasError('password') ? 'is-invalid' : ''" v-model="formData.password" id="password" type="password" class="form-control" name="password"  autocomplete="new-password">
                                    <img id="showPassword" src="/images/icons/eye.png" />
                                    <span v-if="hasError('password')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['password'][0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row m-0">
                                <div class="col-md-10 p-0">
                                    <button :disabled="hasAnyErrors" type="submit" class="btn btn-primary">
                                        {{ __('user.register') }}
                                    </button>
                                    <a class="link-primary d-table justify-content-center mb-0 mt-4" href="{{ route('login') }}">
                                        {{ __('auth.alreadyRegistered') }}
                                    </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </register-component>
            </div>
        </div>
    </div>
</div>
@endsection
