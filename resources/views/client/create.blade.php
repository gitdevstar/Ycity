@extends('layouts.imageLeft')
@section('headTitle')
    {{ __('client.create_new_client') }} - {{ config('app.name', 'Laravel') }}
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
            <div id="app2" v-cloak>
                <client-component inline-template>
                    <div>
                        <form class="hideOnSubmit" method="POST" @submit.prevent="createClient()" @change="clearError" @focusout="clearError">
                            @csrf
                            <h2 class="pb-3 mb-0">{{ __('client.create_new_client') }}</h2>
                            <div class="alert alert-danger" v-if="error">
                                Bitte prüfen Sie Ihre Eingaben.
                            </div>
                            <div class="form-group">
                                <label for="name">{{ __('client.company_name') }}</label>
                                <input v-model="formData.name" :class="hasError('name') ? 'is-invalid' : ''" name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['name'][0] }}</strong>
                                    </span>
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('client.company_description') }}</label>
                                <textarea v-model="formData.description" :class="hasError('description') ? 'is-invalid' : ''"  name="description" type="text" class="form-control @error('description') is-invalid @enderror" id="description"  autocomplete="description">{{ old('description') }}</textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['description'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="street">{{ __('client.streetNr') }}</label>
                                <input v-model="formData.street" :class="hasError('street') ? 'is-invalid' : ''" name="street" type="text" class="form-control @error('street') is-invalid @enderror" id="street" value="{{ old('street') }}"  autocomplete="street">
                                <span v-if="hasError('street')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['street'][0] }}</strong>
                                    </span>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="plz">{{ __('client.plz') }}</label>
                                    <input v-on:blur="getPlace" v-model="formData.plz" :class="hasError('plz') ? 'is-invalid' : ''" name="plz" maxlength="4" type="text" class="form-control @error('plz') is-invalid @enderror" id="plz" value="{{ old('plz') }}"  autocomplete="plz">
                                    <span v-if="hasError('plz')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['plz'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="plz_ort">{{ __('client.place') }}</label>
                                    <input style="background-color: #EFEFEF" readonly name="plz_ort" type="text" class="form-control @error('plz_ort') is-invalid @enderror" id="plz_ort" value="{{ old('plz_ort') }}"  autocomplete="plz_ort">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('client.email') }}</label>
                                <input v-model="formData.email" :class="hasError('email') ? 'is-invalid' : ''" name="email" type="text" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ Auth::user()->email }}"  autocomplete="email">
                                <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['email'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="website">{{ __('client.website') }}</label>
                                <input v-model="formData.website" :class="hasError('website') ? 'is-invalid' : ''" name="website" type="text" class="form-control @error('website') is-invalid @enderror" id="website" value="{{ old('website') }}"  autocomplete="website">
                                <span v-if="hasError('website')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['website'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group mb-4">
                                <label for="telephone">{{ __('client.telephone') }}</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+41</span>
                                    </div>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" v-model="formData.telephone" :class="hasError('telephone') ? 'is-invalid' : ''" name="telephone" maxlength="9" type="text" class="form-control" id="telephone" value="{{ old('telephone') }}"  autocomplete="telephone">
                                </div>
                                <span v-if="hasError('telephone')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['telephone'][0] }}</strong>
                                </span>
                            </div>
                            <a class="btn btn-outline-primary float-left mr-2" href="javascript:history.back()"><i class="fa fa-arrow-left mr-2"></i>{{(__('main.back'))}}</a>
                            <button :disabled="hasAnyErrors" type="submit" class="btn btn-primary">{{ __('client.create') }}</button>
                        </form>
                        <div id="clientSuccess">
                            <div class="alert-success alert" >
                                Client erfolgreich erstellt.
                            </div>
                            <a href="/ycity" class="btn-outline d-table">{{ __('main.go_to_dashboard') }}</a>
                        </div>
                    </div>
                </client-component>
            </div>
    </div>
@endsection
