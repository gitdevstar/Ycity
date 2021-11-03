<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script src="{{ asset('js/jquery-min.js') }}"></script>
    @include("global.head")
    <title>@yield('headTitle')</title>
</head>
<body @if(Auth::check() AND Session::get('dark_mode') == 1) class="dark-mode" @endif>
    <div>
        @include("global.navi")
        <main class="mainContent bg-white">
            <div class="row m-0">
                <div id="blockLeft" style="border-right: 0px;" class="col-xs-10 col-lg-6 p-4">
                    <div id="blockLeftInner" class="w-100 backgroundCarousel">
                        @yield('blockLeft')
                    </div>
                </div>
                <div id="blockRight" style="background-color:#FFF;"  class="col-xs-10 col-lg-4 p-0 notFixed noBorderTablet">
                    <div id="blockRightInner" class="showOnLoad">
                        @yield('blockRight')
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include("global.footer")
    @if(Auth::check())
        @include("global.notifications")
    @endif
    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css" rel="stylesheet" />
    <script>
        $(document).ready(function(){
            $('.carousel').slick({
                dots: true,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 3500,
                infinite: true,
                speed: 500,
            });
        });
    </script>
</body>
</html>
