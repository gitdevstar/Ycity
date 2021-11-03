@extends('layouts.fullpage')

@section('headTitle')
    {{ __('main.settings') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('content')
    @include('global.userNavi')
@endsection

