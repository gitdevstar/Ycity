@extends('layouts.app')

@section('headTitle')
    {{$client->name}} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockLeft')
<div class="container-fluid">
    <h2 class="mb-0 pb-3">{{__('client.company_projects')}}</h2>

</div>
@endsection


@section('blockRight')
    <h2 class="mb-0 pb-3">{{$client->name}}</h2>
    <p>{{$client->description}}</p>
    <h4>{{ __('client.adress') }}</h4>
    <p>{{$client->street}} <br /> {{$client->plz}} {{$place->place}} {{$place->canton_short}}  </p>
    <h4>{{ __('client.contact') }}</h4>
    <p>
        <a class="link-underline" href="mailto:{{$client->email}}">{{$client->email}}</a>
        <br />
        <a class="link-underline" href="tel:{{$client->telephone}}">{{$client->telephone}}</a>
        <br />
        <br />
        <a class="link-underline" href="{{$client->website}}" target="_blank">{{$client->website}}</a>
    </p>
@endsection

