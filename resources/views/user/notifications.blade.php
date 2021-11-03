@extends('layouts.app')

@section('headTitle')
    {{ __('navi.messages') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockRight')
    <div class="hideOnMobile">
        @include('global.userNavi')
    </div>
@endsection

@section('blockLeft')
<p class="h1">{{ __('navi.messages') }}</p>

@endsection
