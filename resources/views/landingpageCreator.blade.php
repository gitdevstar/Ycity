@extends('layouts.imageLeft')

@section('headTitle')
    {{ __('creator.what_fits_you')}} - {{ config('app.name', 'Laravel') }}
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
                        <h2>{{ __('creator.what_fits_you')}}</h2>
                    </div>
                </div>
                <div class="row table mb-4">
                    <div class="col-sm">
                        <a href="/ycity/creator/create?type=company" role="button" aria-pressed="true" class="mb-1 d-block">
                            <div class="btn-lg btn-outline text-left d-block selfemployed">
                                <span>{{ __('creator.selfemployed')}}</span>
                                <br />
                                <small class="color-grey">{{ __('creator.selfemployed_text')}}</small>
                            </div>
                        </a>
                        <a href="/ycity/creator/create?type=no-company" role="button" aria-pressed="true" class="d-block">
                            <div class="btn-lg btn-outline text-left d-block">
                                <span>{{ __('creator.not_selfemployed')}}</span>
                                <br />
                                <small class="color-grey">{{ __('creator.not_selfemployed_text')}}</small>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <a class="btn-sm btn-primary float-left mt-4" href="/ycity"><i class="fa fa-arrow-left mr-2"></i>{{(__('main.back'))}}</a>
                    </div>
                </div>
            </div>
        </div>
@endsection
