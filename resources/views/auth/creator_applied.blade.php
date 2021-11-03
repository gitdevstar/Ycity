@extends('layouts.fullpage')

@section('headTitle')
    Bewerbung angenommen - {{ config('app.name', 'Laravel') }}
@endsection

@section('content')
    <h2>Creator angenommen</h2>
    <p>Die Bewerbung des Creators wurde erfolgreich angenommen und der Creator wurde benachrichtigt.</p>
@endsection
