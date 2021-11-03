@extends('layouts.app')

@section('headTitle')
    {{ __('client.clients') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('blockRight')
    <div class="hideOnMobile">
        @include('global.userNavi')
    </div>
@endsection

@section('blockLeft')
    <h2 class="mb-3">{{ __('client.clients') }}</h2>
    @forelse($clients as $client)
        <a class="btn d-block btn-outline mb-3 text-left" href="/ycity/client/{{$client->id}}/edit">
            <span>{{$client->name}}</span>
            <span class="d-inline-block float-right color-grey" >{{ ucfirst(__('main.edit'))}}</span>
        </a>
    @empty
        <p>{{ __('client.no_clients') }}</p>
    @endforelse

    <a class="btn btn-primary btn-sm" href="/ycity/client/create"><i class="fa fa-plus-circle mr-2"></i>Neuen Client erstellen</a>
@endsection

