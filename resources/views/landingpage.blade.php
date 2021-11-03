@extends('layouts.imageLeft')

@section('headTitle')
    {{ __('main.what_are_you')}} - {{ config('app.name', 'Laravel') }}
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
            <div class="container px-0">
                <div class="row mb-4">
                    <div class="col-sm">
                        <h2>{{ __('main.i_am_a')}}...</h2>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm">
                        <a href="/ycity/client/create" role="button" aria-pressed="true" class="mb-1 d-block">
                            <div class="btn-lg btn-outline text-left d-block client">
                                <span>Client</span>
                                <br />
                                <small class="color-grey">{{ __('main.i_am_a_client')}}</small>
                            </div>
                        </a>
                        <a href="/ycity/creator" role="button" aria-pressed="true" class="d-block">
                            <div class="btn-lg btn-outline text-left d-block">
                                <span>Creator</span>
                                <br />
                                <small class="color-grey">{{ __('main.i_am_a_creator')}}</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
@endsection
