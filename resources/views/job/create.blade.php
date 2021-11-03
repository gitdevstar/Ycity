@extends('layouts.fullpage')

@section('headTitle')
    {{ __('job.create_new_job') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('content')
    @inject('homecontroller', 'App\Http\Controllers\HomeController')
<div id="createJobContainer" class="container-fluid">
    <div id="app2" v-cloak>
        <job-component inline-template>
            <div>
                <form class="hideOnSubmit" method="POST" @submit.prevent="createJob()" @focusout="clearError">
                    <div class="alert alert-danger" v-if="error">
                        {{ __('job.job_failed') }}
                    </div>
                    <div class="alert-success alert" v-if="success">
                        {{ __('job.job_success') }}
                    </div>
                    @csrf
                    @if (Auth::check())
                        <b>{{$client}}</b>
                    @endif
                    <!---
                    <div class="row m-0 m-0">
                        <p class="h1 py-2">{{ __('job.create_new_job') }}</p>
                        <p class="d-block pb-2">{{ __('job.create_new_job_text') }}</p>
                        <div class="col-md-10 p-0">
                            <div class="mt-3">
                                <button type="button" v-on:click="goNext($event)" class="btn btn-primary btn-sm mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="row m-0 m-0">
                        <p class="h1 py-2">{{__('job.category_title')}}</p>
                        <p class="d-block pb-2">{{__('job.category_text')}}</p>
                        <div class="col-md-10 p-0">
                            <select v-model="formData.categories_fk" :class="hasError('categories_fk') ? 'is-invalid' : ''" name="categories_fk" class="d-none form-control" id="categories_fk">
                                <option value="">bitte auswählen</option>
                                @foreach($categories AS $category)
                                    @if (old('categories_fk') == $category->id)
                                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                    @else
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="categoryBox">
                                @foreach($categories AS $category)
                                    <div v-on:click="toggleCategory('{{ $category->name }}', $event, '{{ $category->id }}')" class="categoryBoxItem" data-category="{{md5($category->id)}}">
                                        <img class="icon" src="/images/icons/category/{{ $category->icon }}.svg" />
                                        <img class="icon-selected" src="/images/icons/category/{{ $category->icon }}_active.svg" />
                                        <span>{{ $category->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <span v-if="hasError('categories_fk')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['categories_fk'][0] }}</strong>
                            </span>
                            <div class="mt-4 mb-5 d-block">
                                <button type="button" v-on:click="validateSelect($event, 'categories_fk')"  :disabled="hasError('categories_fk')" class="btn btn-primary btn-sm mr-3 d-table" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                            <p class="mt-3">{{ __('job.category_request_text') }}</p>
                            <button type="button" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/nicolas-kubat/60min'});return false;" class="btn btn-outline-primary btn-sm" >{{ __('job.request') }}<i class="fa fa-arrow-right ml-2"></i></button>
                        </div>
                    </div>
                    <div class="row m-0 m-0">
                        <div class="noContactCategory">
                            <p class="h1 py-2">{{__('job.subcategory_title')}}</p>
                            <p class="d-block pb-2">{{__('job.subcategory_text')}}</p>
                        </div>
                        <div class="col-md-10 p-0">
                            <select v-model="formData.subcategories_fk" :class="hasError('subcategories_fk') ? 'is-invalid' : ''" name="subcategories_fk" class="d-none form-control" id="subcategories_fk">
                                <option value="">bitte auswählen</option>
                                @foreach($subcategories AS $subcategory)
                                    @if (old('categories_fk') == $subcategory->id)
                                        <option value="{{ $subcategory->id }}" selected>{{ $subcategory->name }}</option>
                                    @else
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div id="subcategories" class="subcategoryBox">
                                @foreach($subcategories AS $subcategory)
                                    <div v-on:click="toggleSubcategory('{{ $subcategory->name }}', $event, '{{ $subcategory->id }}')" class="subcategoryBoxItem" data-subcategory="{{md5($subcategory->id)}}" data-category="{{md5($subcategory->categories_fk)}}">
                                        <img class="icon" src="/images/icons/subcategory/{{ $subcategory->icon }}.svg" />
                                        <img class="icon-selected" src="/images/icons/subcategory/{{ $subcategory->icon }}_active.svg" />
                                        <span>{{ $subcategory->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <span v-if="hasError('subcategories_fk')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['subcategories_fk'][0] }}</strong>
                            </span>
                            <div id="categoryContact" class="mb-2">
                                <p class="h1 py-2">{{__('job.no_subcategory_yet')}}</p>
                                <p>{{ __('job.category_needs_request') }}</p>
                                <button type="button" v-on:click="goBack($event)" class="btn btn-primary btn-sm mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                <button type="button" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/nicolas-kubat/60min'});return false;"  class="btn btn-outline-primary btn-sm mr-3" >{{ __('job.request') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                            <div class="mt-3 noContactCategory">
                                <button type="button" v-on:click="goBack($event)" class="btn btn-primary btn-sm mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                <button type="button" v-on:click="validateSelect($event, 'subcategories_fk')"  :disabled="hasError('subcategories_fk')" class="btn btn-primary btn-sm mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-md-10 p-0">
                            <p class="h1 py-2">{{__('job.specification_title')}}</p>
                            <p class="d-block pb-2">{{__('job.specification_text')}}</p>
                        </div>
                        <div class="pl-0 pr-0 pr-md-4 col-md-5">
                            <p class="h4 py-2 font-weight-bold">{{__('job.active')}}</p>
                            <ul id="chosenSpecifications" class="categoryBox connectedSortable">
                            </ul>
                        </div>
                        <div class="pr-0 pl-0 pl-md-4 col-md-5">
                            <p class="h4 py-2 font-weight-bold">{{__('job.available')}}</p>
                            <ul id="specifications" class="categoryBox connectedSortable">
                            </ul>
                        </div>
                        <div class="col-md-10 p-0">
                            <div id="noContact" class="mt-3">
                                <div class="d-block mb-4"><span class="h3 font-weight-bold" id="priceTotal">0</span> CHF</div>
                                <button type="button" v-on:click="goBack($event)" class="btn btn-primary btn-sm mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                <button type="button" v-on:click="addSpecifications($event)"  class="btn btn-primary btn-sm mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                            <div id="contact" class="mt-3">
                                <p>{{ __('job.request_text') }}</p>
                                <button type="button" onclick="Calendly.initPopupWidget({url: 'https://calendly.com/nicolas-kubat/60min'});return false;"  class="btn btn-primary btn-sm mr-3" >{{ __('job.request') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 m-0">
                        <p class="h1 py-2">{{ __('job.job_name') }}</p>
                        <div class="col-md-10 p-0"> <input v-model="formData.name" :class="hasError('name') ? 'is-invalid' : ''" name="name" type="text" class="form-control" id="name" placeholder="{{ __('job.jobtitle_placeholder') }}" value="{{ old('name') }}"  autocomplete="name">
                            <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['name'][0] }}</strong>
                            </span>
                            <div class="mt-3">
                                <button type="button" v-on:click="goBack($event)" class="btn btn-primary btn-sm mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                <button type="button" v-on:click="validateInputField($event, 'name')"  :disabled="hasError('name')" class="btn btn-primary btn-sm mr-3 float-left" >{{ __('main.next') }}<i class="fa fa-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col-md-10 mb-3 p-0">
                            <p class="h1 py-2">{{ __('job.additional_info') }}</p>
                            <label class="mt-3"  for="name">{{ __('job.name') }}</label>
                            <h2 class="m-0 d-block w-100">@{{ formData.name }}</h2>
                            <div class="form-row m-0 mb-3">
                                <div class="col-md-5 p-0">
                                    <label class="mt-3"  for="name">{{ __('job.category') }}</label>
                                    <span class="h4 d-block w-100">@{{ formData.categoryText }}</span>
                                </div>
                                <div class="col-md-5 p-0">
                                    <label class="mt-3"  for="name">{{ __('job.subcategory') }}</label>
                                    <span class="h4 d-block w-100">@{{ formData.subcategoryText }}</span>
                                </div>
                            </div>
                            <div class="form-row m-0 mb-3">
                                <div class="col-md-10 p-0">
                                    <label class="mt-3"  for="name">{{ __('job.specifications') }}</label>
                                    <span class="h4 d-block w-100">@{{ formData.specificationsText }}</span>
                                </div>
                            </div>
                            <label class="mt-3"  for="description">{{ __('job.description') }}</label>
                            <textarea  v-model="formData.description" :class="hasError('description') ? 'is-invalid' : ''" name="description" type="text" class="form-control" id="description" placeholder="{{ __('job.description_placeholder') }}" autocomplete="description">{{ old('description') }}</textarea>
                            <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['description'][0] }}</strong>
                            </span>
                            <div class="form-row m-0 mt-3">
                                <input v-model="formData.location" v-on:click="toggleLocation()" name="location" class="mr-2" type="checkbox"id="location">
                                <label class="form-check-label" for="location">
                                    {{ __('job.location_dependent') }}
                                </label>
                            </div>
                            <div id="locationInfo">
                                <div class="form-row mx-0 mt-3">
                                    <label for="street">{{ __('job.streetNr') }}</label>
                                    <input v-model="formData.street" :class="hasError('street') ? 'is-invalid' : ''" name="street" type="text" class="form-control" id="street" value="{{ old('street') }}"  autocomplete="street">
                                    <span v-if="hasError('street')" class="invalid-feedback" role="alert">
                                        <strong>@{{errors['street'][0] }}</strong>
                                    </span>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="form-group col-md-4">
                                        <label for="plz">{{ __('job.plz') }}</label>
                                        <input v-on:blur="getPlace" v-model="formData.plz" :class="hasError('plz') ? 'is-invalid' : ''" name="plz" maxlength="4" type="text" class="form-control" id="plz" value="{{ old('plz') }}"  autocomplete="plz">
                                        <span v-if="hasError('plz')" class="invalid-feedback" role="alert">
                                            <strong>@{{errors['plz'][0] }}</strong>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="plz_ort">{{ __('job.place') }}</label>
                                        <input style="background-color: #EFEFEF" readonly name="plz_ort" type="text" class="form-control" id="plz_ort" value="{{ old('plz_ort') }}"  autocomplete="plz_ort">
                                    </div>
                                </div>
                            </div>
                            <span v-if="hasError('payment_types_fk')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['payment_types_fk'][0] }}</strong>
                            </span>
                            <label class="mt-3" for="complexities_fk">{{ __('job.complexity') }}</label>
                            <select v-model="formData.complexities_fk" :class="hasError('complexities_fk') ? 'is-invalid' : ''" name="complexities_fk" class="form-control" id="complexities_fk" selected="{{ old('complexities_fk') }}">
                                <option value="">bitte auswählen</option>
                                @foreach($complexities AS $complexity)
                                    @if (old('complexities_fk') == $complexity->id)
                                        <option value="{{ $complexity->id }}" selected>{{ $complexity->name }}</option>
                                    @else
                                        <option value="{{ $complexity->id }}">{{ $complexity->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span v-if="hasError('complexities_fk')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['complexities_fk'][0] }}</strong>
                            </span>
                            <label class="mt-3" for="eventdate">{{ __('job.eventdate') }} ({{ __('job.eventdate_text') }})</label>
                            <input v-model="formData.eventdate" :class="hasError('eventdate') ? 'is-invalid' : ''" name="eventdate" type="date" class="form-control" id="eventdate" value="{{ old('eventdate')}}"  autocomplete="eventdate">
                            <span v-if="hasError('eventdate')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['eventdate'][0] }}</strong>
                            </span>
                            <label class="mt-3" for="end">{{ __('job.end') }}</label>
                            <input v-model="formData.end" :class="hasError('end') ? 'is-invalid' : ''" name="end" type="date" class="form-control" id="end" value="{{ old('end')}}"  autocomplete="end">
                            <span v-if="hasError('end')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['end'][0] }}</strong>
                            </span>
                            <label class="mt-3" for="files">{{ __('job.files') }} ({{ __('main.optional') }})</label>
                            <div class="d-block w-100 mb-4">
                                <input type="file"
                                       id="jobFiles"
                                       class="filepond"
                                       name="jobFiles"
                                       multiple
                                       data-allow-reorder="true"
                                       data-max-file-size="100MB"
                                       accept="image/*,.doc,.docx.pdf" />
                                <span v-if="hasError('files')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['files'][0] }}</strong>
                                </span>
                            </div>
                            <div class="col-md-10 p-0">
                                <p id="advertiseNotice">{{ __('job.advertise_notice') }}: <br /> {{ __('job.advertise_notice_fields') }}</p>
                            </div>
                            <div class="col-md-10 p-0">
                                <div class="mt-3">
                                    <button type="button" v-on:click="goBack($event)" class="btn btn-primary btn-sm mr-3 float-left" ><i class="fa fa-arrow-left mr-2"></i>{{ __('main.back') }}</button>
                                    <button :disabled="hasAnyErrors" type="submit" class="btn btn-primary btn-sm">{{ __('job.advertise_job') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-md-10 p-0">
                    <div id="jobSuccess" class="m-0 m-0">
                        <img src="/images/icons/send.png" style="width: 80px" class="d-table m-auto">
                        @if (Auth::check())
                            <p class="d-block h1 py-4 text-center">{{ __('job.job_advertised') }}</p>
                            <a href="/ycity" class="btn-outline d-table m-auto">{{ __('main.go_to_dashboard') }}</a>
                        @else
                            <p class="d-block h1 py-4 text-center">Fast geschafft! <br /> Registriere dich jetzt, um den Job auszuschreiben.</p>
                            <a href="{{ route('register') }}" class="btn-outline d-table m-auto">{{ __('navi.register') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </job-component>
    </div>
</div>
<script src="{{ asset('js/filepond.js') }}" defer></script>
@endsection
