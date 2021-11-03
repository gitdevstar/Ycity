@extends('layouts.fullpage')

@section('headTitle')
    Bewerbung abgelehnt - {{ config('app.name', 'Laravel') }}
@endsection

@section('content')
    <h2>Creator abgelehnt</h2>
    <p>Die Bewerbung des Creators wurde abgelehnt und der Creator wurde benachrichtigt.</p>
@endsection
