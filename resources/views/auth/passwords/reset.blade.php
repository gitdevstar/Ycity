@extends('layouts.fullpage')

@section('headTitle')
    {{ __('user.password_reset') }} - {{ config('app.name', 'Laravel') }}
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center m-0">
        <div class="col-md-8 p-0">
                <h2 class="mb-4">{{ __('user.password_reset') }}</h2>
                <div class="container-fluid px-0">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row m-0 mb-3">
                            <label for="email" class="col-md-4 col-form-label pl-0">{{ __('user.email') }}</label>

                            <div class="col-md-6 p-0">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row m-0 mb-3">
                            <label for="password" class="col-md-4 col-form-label pl-0">{{ __('user.password') }}</label>

                            <div class="col-md-6 p-0">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row m-0 mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label pl-0">{{ __('user.password_confirm') }}</label>

                            <div class="col-md-6 p-0">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row m-0 mb-3">
                            <div class="col-md-6 offset-md-4 p-0">
                                <button type="submit" class="btn float-right btn-primary">
                                    {{ __('user.password_reset') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
