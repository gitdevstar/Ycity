<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="{{ asset('js/jquery-min.js') }}"></script>
        @include("global.head")
        <title>@yield('headTitle')</title>
        <meta name="title" content="@yield('headTitle')">
    </head>
    <body @isset($map) @if($map == 1) onload="initialize()" @endif @endisset @if(Auth::check() AND Session::get('dark_mode') == 1) class="dark-mode" @endif>
        <div>
            @include("global.navi")
            <main class="mainContent">
                <div class="row m-0">
                    <div id="blockLeft" class="col-xs-10 col-lg-7 p-0">
                        <div class="showOnLoad" id="blockLeftInner">
                            @yield('blockLeft')
                        </div>
                    </div>
                    <div id="blockRight" class="col-xs-10 col-lg-3 p-0">
                        <div id="blockRightInner">
                            @yield('blockRight')
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
