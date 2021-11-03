@extends('layouts.fullpage')

@section('headTitle')
    {{ __('Confirm Password') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center m-0">
        <div class="col-md-8 p-0">
            <h2 class="mb-4">{{ __('Confirm Password') }}</h2>

            {{ __('Please confirm your password before continuing.') }}

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="form-group row m-0">
                    <label for="password" class="col-md-4 col-form-label pl-0">{{ __('Password') }}</label>

                    <div class="col-md-6 p-0">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row m-0 mb-3">
                    <div class="col-md-6 offset-md-4 p-0">
                        <button type="submit" class="btn float-right btn-primary">
                            {{ __('Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
