@extends('layouts.app')

@section('headTitle')
    {{ __('skills.edit_skills') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockRight')
    <div class="hideOnMobile">
        @include('global.userNavi')
    </div>
@endsection

@section('blockLeft')
<div class="moveBefore">
    <p class="h3 pb-3 w-100 d-block">{{ __('skills.edit_my_skills') }}</p>
    <div id="editSkillsContainer">
        <div  id="app2" v-cloak>
            <creator-component inline-template>
                <div>
                    @inject('homecontroller', 'App\Http\Controllers\HomeController')
                    <form class="hideOnSubmit" method="POST" @submit.prevent="updateCreatorSkills('{{$homecontroller::encrypt_decrypt($creators_id,"encrypt")}}')">
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
                        <input type="submit" value="{{__('main.save')}}" class="btn btn-sm btn-primary"/>
                    </form>
                    <div id="creatorSuccess">
                        <div class="alert-success alert" >
                            Skills erfolgreich angepasst.
                        </div>
                        <a href="/ycity/creator/{{$creators_id}}/{{urlencode(Auth::user()->firstname)}}-{{urlencode(Auth::user()->lastname)}}" class="btn-outline d-table">{{ __('portfolio.go_to_portfolio') }}</a>
                    </div>
                    <div id="creatorError">
                        <div class="alert-danger alert" >
                            Skills anpassen fehlgeschlagen, bitte probieren Sie es später nochmals.
                        </div>
                    </div>
                </div>
            </creator-component>
        </div>
    </div>
</div>
<script src="{{ asset('js/filepond.js') }}" defer></script>
@endsection


