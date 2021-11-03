@extends('layouts.imageLeft')
@section('headTitle')
    {{ __('creator.create_new_creator') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    <div class="carousel showOnLoad">
        <div><p class="w-100 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ultrices fermentum</p></div>
        <div><p class="w-100 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ultrices fermentum</p></div>
        <div><p class="w-100 text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ultrices fermentum</p></div>
    </div>
@endsection

@section('blockRight')
    @inject('homecontroller', 'App\Http\Controllers\HomeController')
    <div class="vertical-center">
        <div id="app2" v-cloak>
            <creator-component inline-template>
                <div>
                    <form class="hideOnSubmit" method="POST" enctype="multipart/form-data" @submit.prevent="createCreator()" @change="clearError" @focusout="clearError">
                        @csrf
                        <h2 class="pb-3 mb-0">{{ __('creator.create_new_creator') }}</h2>
                        <div class="alert alert-danger" v-if="error">
                            Bitte prüfen Sie Ihre Eingaben.
                        </div>
                        @if($type == "company")
                            <div class="creatorContent" id="companyInfo">
                                <h3 class="pb-3 mb-0">Firma</h3>
                                <div class="row m-0 mb-2">
                                    <label for="organisation_type">Organisation</label>
                                    <select v-model="organisation.organisation_type" :class="hasError('organisation_type') ? 'is-invalid' : ''" name="organisation_type" class="form-control" id="organisation_type">
                                        <option value="" selected>bitte auswählen</option>
                                        <option value="Einzelfirma">Einzelfirma</option>
                                        <option value="GmbH">GmbH</option>
                                        <option value="AG">AG</option>
                                        <option value="Genossenschaft">Genossenschaft</option>
                                        <option value="Verein">Verein</option>
                                        <option value="Andere">Andere</option>
                                    </select>
                                    <span v-if="hasError('organisation_type')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['organisation_type'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="row m-0 mb-2">
                                    <label for="organisation_name">Name</label>
                                    <input v-model="organisation.organisation_name" :class="hasError('organisation_name') ? 'is-invalid' : ''" name="organisation_name" type="text" class="form-control" id="organisation_name" value="{{ old('organisation_name') }}"  autocomplete="organisation_name">
                                    <span v-if="hasError('organisation_name')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['organisation_name'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="row m-0 mb-2">
                                    <label for="organisation_uid">UID-Nummer (<a class="link-primary" href="https://www.uid.admin.ch/" target="_blank">herausfinden</a>)</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">CHE-</span>
                                        </div>
                                        <input maxlength="11" v-model="organisation.organisation_uid" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" :class="hasError('organisation_uid') ? 'is-invalid' : ''" pattern="\d{3}\.\d{3}\.\d{3}" tabindex="3" placeholder="___.___.___" name="organisation_uid" type="text" class="form-control" id="organisation_uid" value="{{ old('organisation_uid') }}"  autocomplete="organisation_uid">
                                    </div>
                                    <span v-if="hasError('organisation_uid')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['organisation_uid'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="row d-block m-0 mb-2">
                                    <label for="organisation_sva">SVA-Nachweis</label>
                                    <input onclick="clearError()" type="file" class="filepond mb-0" accept="application/pdf" :class="hasError('organisation_sva') ? 'is-invalid' : ''" value="{{ old('organisation_sva') }}"  autocomplete="organisation_sva" name="filepond" id="sva" />
                                    <input type="hidden" id="svaBase64String" />
                                    <span id="sva-feedback" class="invalid-feedback" role="alert">
                                        <strong>{{__('validation.sva_validation')}}</strong>
                                    </span>
                                </div>
                            <!--
                                <div class="row m-0 mb-2">
                                    <label for="organisation_street">{{ __('creator.streetNr') }}</label>
                                    <input v-model="organisation.organisation_street" :class="hasError('organisation_street') ? 'is-invalid' : ''" name="organisation_street" type="text" class="form-control" id="organisation_street" value="{{ old('organisation_street') }}"  autocomplete="organisation_street">
                                    <span v-if="hasError('organisation_street')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['organisation_street'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="row m-0 mb-2">
                                    <div class="col-md-4 pl-0">
                                        <label for="organisation_plz">{{ __('creator.plz') }}</label>
                                        <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" v-on:blur="getPlace('organisation_plz')" v-model="organisation.organisation_plz" :class="hasError('organisation_plz') ? 'is-invalid' : ''" name="organisation_plz" maxlength="4" type="text" class="form-control" id="organisation_plz" value="{{ old('organisation_plz') }}"  autocomplete="organisation_plz">
                                        <span v-if="hasError('organisation_plz')" class="invalid-feedback" role="alert">
                                            <strong>@{{errors['organisation_plz'][0] }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-6 pr-0">
                                        <label for="organisation_plz_ort">{{ __('creator.place') }}</label>
                                        <input style="background-color: #EFEFEF" readonly name="organisation_plz_ort" type="text" class="form-control" id="organisation_plz_ort" value="{{ old('organisation_plz_ort') }}"  autocomplete="organisation_plz_ort">
                                    </div>
                                </div>
                                <div class="row m-0 mb-4">
                                    <label for="organisation_telephone">{{ __('creator.telephone') }}</label>
                                    <div class="input-group mb-0">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+41</span>
                                        </div>
                                        <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" v-model="organisation.organisation_telephone" :class="hasError('organisation_telephone') ? 'is-invalid' : ''" name="organisation_telephone" maxlength="9" type="text" class="form-control" id="organisation_telephone" value="{{ old('organisation_telephone') }}"  autocomplete="organisation_telephone">
                                    </div>
                                    <span v-if="hasError('organisation_telephone')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['organisation_telephone'][0] }}</strong>
                                    </span>
                                </div>
                                -->
                                <div class="row m-0 mb-2">
                                    <div class="col-md-10 p-0">
                                        <div class="mt-3">
                                            <a class="btn-sm btn-outline-primary float-left mr-2" href="/ycity/creator"><i class="fa fa-arrow-left mr-2"></i>{{(__('main.back'))}}</a>
                                            <button :disabled="hasAnyErrors" type="button" v-on:click="validatePreCreatorInfos($event)" class="btn-sm btn-primary mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="creatorType" name="creatorType" value="company" />
                            </div>
                        @else
                             <div class="creatorContent" id="userInfo">
                                 <h3 class="pb-3 mb-0">Private Angaben</h3>
                                 <div class="row m-0 mb-2">
                                     <label for="ahv_nr">AHV-Nummer</label>
                                     <div class="input-group mb-3">
                                         <div class="input-group-prepend">
                                             <span class="input-group-text" id="basic-addon1">756.</span>
                                         </div>
                                         <input maxlength="12" v-model="noOrganisation.ahv_nr" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" :class="hasError('ahv_nr') ? 'is-invalid' : ''" pattern="\d{4}\.\d{4}\.\d{2}" tabindex="3" placeholder="____.____.__" name="ahv_nr" type="text" class="form-control" id="ahv_nr" value="{{ old('ahv_nr') }}"  autocomplete="ahv_nr">
                                     </div>
                                     <span v-if="hasError('ahv_nr')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['ahv_nr'][0] }}</strong>
                                    </span>
                                 </div>
                                 <div class="row m-0 mb-2">
                                     <label class="form-check-label d-block w-100">
                                         Haben Sie Kinder?
                                     </label>
                                     <input v-model="noOrganisation.children" name="children" class="mr-2" type="radio" id="no" value="nein">
                                     <label class="form-check-label mr-3" for="no">
                                         nein
                                     </label>
                                     <input v-model="noOrganisation.children" name="children" class="mr-2" type="radio" id="yes" value="ja">
                                     <label class="form-check-label" for="yes">
                                         ja
                                     </label>
                                     <span v-if="hasError('children')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['children'][0] }}</strong>
                                    </span>
                                 </div>
                                 <div class="row m-0 mb-2">
                                     <label for="nationality">Nationalität</label>
                                     <input v-model="noOrganisation.nationality" :class="hasError('nationality') ? 'is-invalid' : ''" name="nationality" type="text" class="form-control" id="nationality" value="{{ old('nationality') }}"  autocomplete="nationality">
                                     <span v-if="hasError('nationality')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['nationality'][0] }}</strong>
                                    </span>
                                 </div>
                                 <div class="row m-0 mb-2">
                                     <div class="col-md-10 p-0">
                                         <div class="mt-3">
                                             <a class="btn-sm btn-outline-primary float-left mr-2" href="/ycity/creator"><i class="fa fa-arrow-left mr-2"></i>{{(__('main.back'))}}</a>
                                             <button :disabled="hasAnyErrors" type="button" v-on:click="validatePreCreatorInfos($event)" class="btn btn-sm mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                                         </div>
                                     </div>
                                 </div>
                                 <input type="hidden" id="creatorType" name="creatorType" value="no company" />
                             </div>
                        @endif
                        <div class="creatorContent" id="creatorsInfo">
                            <h3 class="pb-3 mb-0">Persönliche Informationen</h3>
                            <div class="row m-0 mb-2">
                                <label for="birthdate">{{ __('creator.birthdate') }}</label>
                                <input v-model="formData.birthdate" :class="hasError('birthdate') ? 'is-invalid' : ''" name="birthdate" type="date" class="form-control" id="birthdate" value="{{ old('birthdate')}}">
                                <span v-if="hasError('birthdate')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['birthdate'][0] }}</strong>
                                </span>
                            </div>
                            <div class="row m-0 mb-2">
                                <label for="description">{{ __('creator.description') }}</label>
                                <textarea v-model="formData.description" :class="hasError('description') ? 'is-invalid' : ''"  name="description" type="text" class="form-control" id="description"  autocomplete="description">{{ old('description') }}</textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['description'][0] }}</strong>
                                </span>
                            </div>
                            <div class="row m-0 mb-2">
                                <label for="street">{{ __('creator.streetNr') }}</label>
                                <input v-model="formData.street" :class="hasError('street') ? 'is-invalid' : ''" name="street" type="text" class="form-control" id="street" value="{{ old('street') }}"  autocomplete="street">
                                <span v-if="hasError('street')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['street'][0] }}</strong>
                                </span>
                            </div>
                            <div class="row m-0 mb-2">
                                <div class="col-md-4 pl-0">
                                    <label for="plz">{{ __('creator.plz') }}</label>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" v-on:blur="getPlace('plz')" v-model="formData.plz" :class="hasError('plz') ? 'is-invalid' : ''" name="plz" maxlength="4" type="text" class="form-control" id="plz" value="{{ old('plz') }}"  autocomplete="plz">
                                    <span v-if="hasError('plz')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['plz'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="col-md-6 pr-0">
                                    <label for="plz_ort">{{ __('creator.place') }}</label>
                                    <input style="background-color: #EFEFEF" readonly name="plz_ort" type="text" class="form-control" id="plz_ort" value="{{ old('plz_ort') }}"  autocomplete="plz_ort">
                                </div>
                            </div>
                            <div class="row m-0 mb-4">
                                <label for="telephone">{{ __('creator.telephone') }}</label>
                                <div class="input-group mb-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+41</span>
                                    </div>
                                    <input onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" v-model="formData.telephone" :class="hasError('telephone') ? 'is-invalid' : ''" name="telephone" maxlength="9" type="text" class="form-control" id="telephone" value="{{ old('telephone') }}"  autocomplete="telephone">
                                </div>
                                <span v-if="hasError('telephone')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['telephone'][0] }}</strong>
                                </span>
                            </div>
                            <div class="row m-0">
                                <button type="button" v-on:click="goBack($event)" class="btn-sm btn-outline-primary mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                <button type="button" v-on:click="validateCreatorInfos($event)" class="btn-sm btn-primary mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                        <div id="creatorsSkills" class="creatorContent">
                            <div class="row m-0 mb-2">
                                <h3 class="pb-3 mb-0">Bewerbung abschliessen</h3>
                            </div>
                            <div class="row m-0 mb-2">
                                <label for="apply_text">Warum möchtest du Y-City beitreten? (Bewerbungstext, Motivationsschreiben)</label>
                                <textarea style="min-height: 200px" v-model="formData.apply_text" :class="hasError('apply_text') ? 'is-invalid' : ''"  name="apply_text" type="text" class="form-control" id="apply_text"  autocomplete="apply_text">{{ old('apply_text') }}</textarea>
                                <span v-if="hasError('apply_text')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['apply_text'][0] }}</strong>
                                </span>
                            </div>
                            <div class="row m-0 mb-2">
                                <label for="website">Link zur Webseite / zum Portfolio URL (optional)</label>
                                <input v-model="formData.website" :class="hasError('website') ? 'is-invalid' : ''" name="website" type="text" class="form-control" id="website" value="{{ old('website') }}"  autocomplete="website">
                                <span v-if="hasError('website')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['website'][0] }}</strong>
                                </span>
                            </div>
                            <div class="row m-0 mb-2">
                                <div class="col-md-10 p-0">
                                    <label for="skillFilter">Was sind deine Skills?</label>
                                    <input placeholder="{{__('main.search')}}" class="form-control" id="skillFilter" type="text" v-on:keyup="filterSkills" v-model="formData.skillFilter" />
                                </div>
                            </div>
                            <div class="row m-0 mb-2">
                                <div class="col-md-10 p-0">
                                    <div id="skillsOutter">
                                        <div id="skillsContainer">
                                            @foreach($skills as $skill)
                                                <label for="{{$skill->id}}">
                                                    <span class="skill" v-on:click="toggleSelectedSkill('{{$skill->name}}', $event)">{{$skill->name}}</span>
                                                    <input id="{{$skill->id}}" type="checkbox" v-model="formData.skills" autocomplete="off" value="{{$homecontroller::encrypt_decrypt($skill->id,"encrypt")}}">
                                                </label>
                                            @endforeach
                                        </div>
                                        <p id="noSkillsFound" class="color-grey">Keine Skills gefunden.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0">
                                <button type="button" v-on:click="goBack($event)" class="btn-sm btn-outline-primary mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                <button :disabled="hasAnyErrors" type="submit" class="btn-sm btn-primary">{{ __('creator.create') }}</button>
                                <img style="display:none; width: 20px;" id="creatorLoad" src="/images/icons/loader.gif" />
                            </div>
                        </div>
                    </form>
                    <div id="creatorSuccess">
                        <div class="alert-success alert" >
                            Bewerbung erfolgreich an Y-City abgeschickt. Du wirst per E-Mail benachrichtigst, sobald Y-City deine Bewerbung bearbeitet hat.
                        </div>
                        <a href="/ycity" class="btn-outline d-table">{{ __('main.go_to_dashboard') }}</a>
                    </div>
                </div>
            </creator-component>
        </div>
        <script src="{{ asset('js/filepond.js') }}"></script>
    </div>
@endsection
