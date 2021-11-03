@extends('layouts.app')

@section('headTitle')
    {{ __('portfolio.portfolio_of') }} {{$creator->firstname}} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockRight')
    <div class="hideOnMobile">
        @include('global.userNavi')
    </div>
@endsection

@section('blockLeft')
<div class="moveBefore">
    <p class="h3 pb-3 w-100 d-block">{{ __('portfolio.edit_my_portfolio') }}</p>
    <div id="editPortfolioContainer">
        <div  id="app2" v-cloak>
            <creator-component inline-template>
                <div>
                    <form class="hideOnSubmit" method="POST" @submit.prevent="uploadPortfolioFiles()">
                        <label for="files">{{ __('portfolio.images') }}</label>
                        <div class="d-block w-100">
                            <input type="file"
                                   id="portfolioFiles"
                                   class="filepond"
                                   name="portfolioFiles"
                                   multiple
                                   data-allow-reorder="true"
                                   data-max-file-size="100MB"
                                   accept="image/*" />
                            <input type="hidden" id="upload" value="0"/>
                            <span v-if="hasError('portfolioUpload')" class="invalid-feedback" role="alert">
                                <strong>@{{errors['portfolioUpload'][0] }}</strong>
                            </span>
                        </div>
                        <input type="submit" value="{{__('main.save')}}" class="btn btn-sm btn-primary"/>
                    </form>
                    <div id="creatorSuccess">
                        <div class="alert-success alert" >
                            Portfolio erfolgreich angepasst.
                        </div>
                        <a href="/ycity/creator/{{Session::get('creator_id')}}/{{urlencode(Auth::user()->firstname)}}-{{urlencode(Auth::user()->lastname)}}" class="btn-outline d-table">{{ __('portfolio.go_to_portfolio') }}</a>
                    </div>
                    <div class="hideOnSubmit mt-4" id="portfolioContainer">
                        @inject('homecontroller', 'App\Http\Controllers\HomeController')
                        @php
                            $i = 1;
                        @endphp
                        @forelse($images as $image)
                            <div class="portfolioItem">
                                <img data-toggle="modal" data-target="#deletePortfolioModal{{$i}}" class="removePortfolioItem" src="/images/icons/close.png" />
                                <img class="showImage" data-src="/uploads/creators/{{$creator->creators_id."/portfolio/".$image}}" src="/uploads/creators/{{$creator->creators_id."/portfolio/".$image}}" />
                                <!-- Delete Portfolio PopUp -->
                                <div class="modal fade" data-backdrop="false" id="deletePortfolioModal{{$i}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 id="popupTitle" class="modal-title" id="exampleModalLabel">{{__('portfolio.delete_portfolio_image')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{__('portfolio.delete_portfolio_text')}}: <b>{{$image}}</b>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-outline" data-dismiss="modal">{{ __('main.close') }}</button>
                                                <form class="float-right" style="display:inline-block;"  method="post">
                                                    @csrf
                                                    <button @click="deletePortfolioFile({{$i}}, '{{$homecontroller::encrypt_decrypt($creator->creators_id,"encrypt")}}','{{$homecontroller::encrypt_decrypt($image,"encrypt")}}')" data-dismiss="modal"  type="submit" class="btn btn-primary">{{ __('main.delete') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @empty
                            <p>{{__('portfolio.no_entries')}}</p>
                        @endforelse
                    </div>
                </div>
            </creator-component>
        </div>
    </div>
</div>
<script src="{{ asset('js/filepond.js') }}" defer></script>
@endsection


