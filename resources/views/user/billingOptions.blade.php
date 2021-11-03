@extends('layouts.app')

@section('headTitle')
    {{ __('navi.billing_options') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockRight')
    <div class="hideOnMobile">
        @include('global.userNavi')
    </div>
@endsection

@section('blockLeft')
<h2>{{ __('navi.billing_options') }}</h2>
<p>
    {{$user->firstname}} {{$user->lastname}}
    <br />
    {{$user->street}}
    <br />
    {{$user->plz}} {{$user->place}} {{$user->canton_short}}
</p>
@endsection
