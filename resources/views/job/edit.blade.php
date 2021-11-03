@extends('layouts.app')

@section('headTitle')
    {{ __('job.edit_job') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
    @inject('homecontroller', 'App\Http\Controllers\HomeController')
        <div id="editJob" class="container-fluid">
            <p class="h5 pb-3">{{ __('job.edit_job') }}</p>
            <div id="app2" v-cloak>
                <job-component inline-template>
                    <div>
                        <div class="alert alert-danger" v-if="error">
                            Bitte prüfen Sie Ihre Eingaben.
                        </div>

                        <div v-if="success">
                            <div class="alert-success alert" >
                                Job erfolgreich überarbeitet.
                            </div>
                            <a href="/ycity/job/{{$job->id}}/{{urlencode($job->name)}}" class="btn-outline d-table">{{ __('job.show_job') }}</a>
                        </div>
                        <form class="hideOnSubmit" method="POST" @submit.prevent="editJob({{$job->id}})" @change="clearError" @focusout="clearError">
                            @csrf
                            @method("PATCH")
                            <div class="form-group">
                                <label for="name">{{ __('job.title') }}</label>
                                <input v-model="formData.name" :class="hasError('name') ? 'is-invalid' : ''" name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="z.B. Video über neu veröffentlichtes Produkt" value="{{ old('name') ?? $job->name }}"  autocomplete="name">
                                <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['name'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('job.description') }}</label>
                                <textarea v-model="formData.description" :class="hasError('description') ? 'is-invalid' : ''" name="description" type="text" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="z.B. Ich habe ein neues Produkt veröffentlicht und brauche nun ein passendes Video, welches das Produkt möglichst gut repräsentiert." autocomplete="description">{{ old('description') ?? $job->description }}</textarea>
                                <span v-if="hasError('description')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['description'][0] }}</strong>
                                </span>
                            </div>
                            <div id="skillsOutter" class="form-group">
                                <label>{{ __('main.skills') }}</label>
                                <div class="col-md-10 p-0 mb-3">
                                    <input placeholder="{{__('main.search')}}" class="form-control" id="skillFilter" type="text" v-on:keyup="filterSkills" v-model="formData.skillFilter" />
                                </div>
                                <div class="col-md-10 p-0">
                                    <div id="skillsContainer">
                                        @foreach($skills as $skill)
                                            @if(array_key_exists($skill->id, $activeSkills))
                                                <label for="{{$skill->id}}">
                                                    <span class="skill selected" v-on:click="toggleSelectedSkill('{{$skill->name}}', $event)">{{$skill->name}}</span>
                                                    <input id="{{$skill->id}}" type="checkbox" v-model="formData.skills" value="{{$homecontroller::encrypt_decrypt($skill->id,"encrypt")}}">
                                                </label>
                                            @else
                                                <label for="{{$skill->id}}">
                                                    <span class="skill" v-on:click="toggleSelectedSkill('{{$skill->name}}', $event)">{{$skill->name}}</span>
                                                    <input id="{{$skill->id}}" type="checkbox" v-model="formData.skills" value="{{$homecontroller::encrypt_decrypt($skill->id,"encrypt")}}">
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                    <p id="noSkillsFound" class="color-grey">{{__('main.no_skills_found')}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment_types_fk">{{ __('job.payment_type') }}</label>
                                <select v-model="formData.payment_types_fk" :class="hasError('payment_types_fk') ? 'is-invalid' : ''" name="payment_types_fk" class="form-control" id="payment_types_fk">
                                    <option value="">bitte auswählen</option>
                                    @foreach($payment_types AS $payment_type)
                                        @if (old('payment_types_fk') ?? $job->payment_types_fk == $payment_type->id)
                                            <option value="{{ $payment_type->id }}" selected>{{ $payment_type->name }}</option>
                                        @else
                                            <option value="{{ $payment_type->id }}">{{ $payment_type->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span v-if="hasError('payment_types_fk')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['payment_types_fk'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="complexities_fk">{{ __('job.complexity') }}</label>
                                <select v-model="formData.complexities_fk" :class="hasError('complexities_fk') ? 'is-invalid' : ''" name="complexities_fk" class="form-control" id="complexities_fk">
                                    <option value="">bitte auswählen</option>
                                    @foreach($complexities AS $complexity)
                                        @if (old('complexities_fk') ?? $job->complexities_fk == $complexity->id)
                                            <option value="{{ $complexity->id }}" selected>{{ $complexity->name }}</option>
                                        @else
                                            <option value="{{ $complexity->id }}">{{ $complexity->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span v-if="hasError('complexities_fk')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['complexities_fk'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="complexity_fk">{{ __('job.status') }}</label>
                                <select v-model="formData.status_fk" :class="hasError('status_fk') ? 'is-invalid' : ''" name="status_fk" class="form-control @error('status') is-invalid @enderror" id="status_fk">
                                    <option value="">bitte auswählen</option>
                                    @foreach($status AS $state)
                                        @if (old('status_fk') ?? $job->status_fk == $state->id)
                                            <option value="{{ $state->id }}" selected>{{ $state->name }}</option>
                                        @else
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span v-if="hasError('$state')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['$state'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="end">{{ __('job.end') }}</label>
                                <input v-model="formData.end" :class="hasError('end') ? 'is-invalid' : ''" name="end" type="date" class="form-control @error('end') is-invalid @enderror" id="end" value="{{ old('end') ?? $job->end }}"  autocomplete="end">
                                <span v-if="hasError('end')" class="invalid-feedback" role="alert">
                                    <strong>@{{errors['end'][0] }}</strong>
                                </span>
                            </div>
                            <button :disabled="hasAnyErrors" type="submit" class="btn btn-primary btn-sm">{{ __('main.save') }}</button>
                        </form>
                    </div>
                </job-component>
            </div>
        </div>
@endsection
