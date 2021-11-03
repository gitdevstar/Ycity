<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="{{ asset('js/jquery-min.js') }}"></script>
        @include("global.head")
        <title>@yield('headTitle')</title>
        <script src="{{ asset('js/jquery-ui.js') }}"></script>
    </head>
    <body @if(Auth::check() AND Session::get('dark_mode') == 1) class="dark-mode" @endif>
        <div>
            @include("global.navi")
            <main class="mainContent bg-white">
                <div id="fullpage" class="showOnLoad container">
                    <div class="row m-0 align-items-center">
                        <div class="col-md-10 p-0">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
            @include("global.footer")
        </div>
        @if(Auth::check())
            @include("global.notifications")
        @endif
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
