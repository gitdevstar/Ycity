@extends('layouts.app')

@section('headTitle')
    {{ __('client.edit_client') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    @inject('homecontroller', 'App\Http\Controllers\HomeController')
        <div class="container-fluid">

            <span data-toggle="modal" data-target="#deleteConfirmModal" class="btn btn-outline float-right color-grey ml-2">{{ ucfirst(__('main.delete')) }}</span>
            <div id="app2" v-cloak>
                <client-component inline-template>
                    <div>
                        <div class="modal fade" data-backdrop="false" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 id="popupTitle" class="modal-title" id="exampleModalLabel">{{$client->name}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bist du dir sicher, dass du diesen Client löschen möchtest? Alle dazugehörigen Jobs werden gelöscht.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">abbrechen</button>
                                        <button @click="deleteClient({{$client->id}})" data-id="" data-dismiss="modal"  id="deleteClientButton" class="btn btn-primary">Löschen</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="deleted">
                            <div class="alert-success alert">
                                Client erfolgreich gelöscht.
                            </div>
                            <a href="/ycity" class="btn-outline d-table">{{ __('main.go_to_dashboard') }}</a>
                        </div>
                        <div class="alert alert-danger" v-if="error && !deleted">
                            Bitte prüfen Sie Ihre Eingaben.
                        </div>
                        <div class="alert-success alert" v-if="success  && !deleted">
                            Client erfolgreich angepasst.
                        </div>
                        <form v-if="!deleted" method="POST" @submit.prevent="updateClient({{$client->id}})" @change="clearError" @focusout="clearError">
                            @csrf
                            @method('PUT')
                            <h2 class="pb-3">{{ __('client.edit_client') }}</h2>
                            <input type="hidden" id="formType" value="edit" />
                            <div class="form-group">
                                <label for="name">{{ __('client.company_name') }}</label>
                                <input v-model="formData.name" :class="hasError('name') ? 'is-invalid' : ''" name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') ?? $client->name }}"  autocomplete="name" autofocus>
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['name'][0] }}</strong>
                                </span>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('client.company_description') }}</label>
                                <textarea v-model="formData.description" :class="hasError('description') ? 'is-invalid' : ''"  name="description" type="text" class="form-control @error('description') is-invalid @enderror" id="description"  autocomplete="description">{{ old('description') ?? $client->description }}</textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['description'][0] }}</strong>
                                </span>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="street">{{ __('client.streetNr') }}</label>
                                <input v-model="formData.street" :class="hasError('street') ? 'is-invalid' : ''" name="street" type="text" class="form-control @error('street') is-invalid @enderror" id="street" value="{{ old('street') ?? $client->street }}"  autocomplete="street">
                                <span v-if="hasError('street')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['street'][0] }}</strong>
                                </span>
                                @error('street')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="plz">{{ __('client.plz') }}</label>
                                    <input v-on:blur="getPlace" v-model="formData.plz" :class="hasError('plz') ? 'is-invalid' : ''" name="plz" maxlength="4" type="text" class="form-control @error('plz') is-invalid @enderror" id="plz" value="{{ old('plz') ?? $client->plz }}"  autocomplete="plz">
                                    <span v-if="hasError('plz')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['plz'][0] }}</strong>
                                    </span>
                                    @error('plz')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="plz_ort">{{ __('client.place') }}</label>
                                    <input readonly name="plz_ort" type="text" class="form-control @error('plz_ort') is-invalid @enderror" id="plz_ort" value="{{ old('plz_ort') }}"  autocomplete="plz_ort">
                                    @error('plz_ort')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('client.email') }}</label>
                                <input v-model="formData.email" :class="hasError('email') ? 'is-invalid' : ''" name="email" type="text" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') ?? $client->email }}"  autocomplete="email">
                                <span v-if="hasError('email')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['email'][0] }}</strong>
                                </span>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="website">{{ __('client.website') }}</label>
                                <input v-model="formData.website" :class="hasError('website') ? 'is-invalid' : ''" name="website" type="text" class="form-control @error('website') is-invalid @enderror" id="website" value="{{ old('website') ?? $client->website }}"  autocomplete="website">
                                <span v-if="hasError('website')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['website'][0] }}</strong>
                                </span>
                                @error('website')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="telephone">{{ __('client.telephone') }}</label>
                                <input v-model="formData.telephone" :class="hasError('telephone') ? 'is-invalid' : ''" name="telephone" maxlength="12" type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" value="{{ old('telephone') ?? $client->telephone }}"  autocomplete="telephone">
                                <span v-if="hasError('telephone')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['telephone'][0] }}</strong>
                                </span>
                                @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button :disabled="hasAnyErrors" type="submit" class="btn btn-primary">{{ __('client.save') }}</button>
                        </form>
                    </div>
                </client-component>
            </div>
        </div>
@endsection
