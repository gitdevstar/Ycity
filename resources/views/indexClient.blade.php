<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="{{ asset('js/jquery-min.js') }}"></script>
        @include("global.head")
        <link href="{{ asset('css/frontpage.css?v=').time()}}" rel="stylesheet">
        <title>{{ config('app.name', 'Laravel') }} | {{ __('index.client_meta_title')}}</title>
        <meta name="description" content="{{ __('index.client_meta_text')}}">

        <!-- Og Infos-->
        <meta property="og:title" content="{{ __('index.client_meta_title')}}" />
        <meta property="og:description" content="{{ __('index.client_meta_text')}}">
        <meta property="og:image" content="/favicon.png">
        <meta name="title" content="Tap into the creativity of tomorrow">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/gh/dixonandmoe/rellax@master/rellax.min.js"></script>
        <link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.6.8/plyr.polyfilled.js"></script>
    </head>
    <body>
    @include("global.navi")
    <div class="mainContent">
        <div class="section py-5" id="sectionHeader">
            <!-- <h1 id="bannerTitle">{{ __('index.header_title')}}</h1>
            <p id="bannerText">{{ __('index.header_text')}}</p>
            <div class="rellax circle" data-rellax-speed="4">
                <img src="/images/symbols/circle.svg" />
            </div>
            <div class="rellax triangle" data-rellax-speed="-3">
                <img src="/images/symbols/triangle.svg" />
            </div>
            <div class="rellax rectangle" data-rellax-speed="-6">
                <img src="/images/symbols/rectangle.svg" />
            </div> -->

            <div class="project-selection">
                <video playsinline muted loop autoplay>
                    <source src="/videos/ycity-image-video.mp4" type="video/mp4">
                </video>
                <h1>Let's create a <span>Video</span></h1>
                <div class="quickstart">
                    <form action="/ycity/job/create">
                        <select name="category" style="background: url(images/icons/arrow-down.svg) calc(100% - 24px) / 15% 17% no-repeat;";
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                            </select>
                            <select name="subcategory" style="background: url(images/icons/arrow-down.svg) calc(100% - 24px) / 15% 17% no-repeat;">
                                @foreach($subcategories as $subcategory)
                                    <option data-cat="{{$subcategory->categories_fk}}" value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                                @endforeach
                            </select>
                            <input type="submit" value="Get Started">
                    </form>
                </div>
            </div>

            <p id="bannerText">{{ __('index.header_text')}}</p>

        </div>
        <!-- <div id="sectionSlider" class="section py-5">
            <div id="carousel">
                <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/create?category=Video'"><div class="carousel-element-inner"><img src="/images/icons/category/film.svg" />Video</div></div></div>
                <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/create?category=Fotografie'"><div class="carousel-element-inner"><img src="/images/icons/category/camera.svg" />Photo</div></div></div>
                <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/create?category=Video'"><div class="carousel-element-inner"><img src="/images/icons/category/film.svg" />Video</div></div></div>
                <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/create?category=Social Media'"><div class="carousel-element-inner"><img src="/images/icons/category/smartphone.svg" />Social Media</div></div></div>
                <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/create?category=Audio'"><div class="carousel-element-inner"><img src="/images/icons/category/headphones.svg" />Audio</div></div></div>
                <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/create?category=Design'"><div class="carousel-element-inner"><img src="/images/icons/category/pen-tool.svg" />Design</div></div></div>
            </div>
        </div> -->
        <div class="section pb-5" id="sectionProjects">
            <div class="rellax rectangle-3" data-rellax-speed="3">
                <img src="/images/symbols/rectangle-2.svg" />
            </div>
            <div class="rellax circle-3" data-rellax-speed="-3">
                <img src="/images/symbols/circle.svg" />
            </div>
            <div class="container-fluid p-0">
                <div class="row m-0 d-none d-sm-flex">
                    <div class="col-5 col-sm-5 p-0 pr-2">
                        <div class="project" style="background-image: url('/images/frontpage/placeholder.png');">
                            <video playsinline muted loop src="/videos/wildlife.mp4"></video>
                            <div class="imageDescription">
                                <p>Wildlife - Special Edition (Teaser)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-5 p-0 pl-2" style="padding:0px">
                        <div class="project" style="background-image: url('/images/frontpage/placeholder.png');">
                            <video playsinline muted loop src="/videos/immobilien1.mp4"></video>
                            <div class="imageDescription">
                                <p>Immobilienvideo x Doris Bader Immobilien</p>
                            </div>
                        </div>
                        <div class="project" style="background-image: url('/images/frontpage/placeholder.png');">
                            <video playsinline muted loop src="/videos/immobilien2.mp4"></video>
                            <div class="imageDescription">
                                <p>Immobilienvideo x The Dolder Grand</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3 d-block d-sm-none">
                    <div class="project" style="background-image: url('/images/frontpage/placeholder.png');">
                        <video playsinline muted loop autoplay src="/videos/wildlife.mp4"></video>
                        <div class="imageDescription">
                            <p>Lorem Ipsum Sit Date</p>
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3 d-block d-sm-none">
                    <div class="project" style="background-image: url('/images/frontpage/placeholder.png');">
                        <video playsinline muted loop autoplay src="/videos/immobilien1.mp4"></video>
                        <div class="imageDescription">
                            <p>Lorem Ipsum Sit Date</p>
                        </div>
                    </div>
                </div>
                <div class="row m-0 mb-3 d-block d-sm-none">
                    <div class="project" style="background-image: url('/images/frontpage/placeholder.png');">
                        <video playsinline muted loop autoplay src="/videos/immobilien2.mp4"></video>
                        <div class="imageDescription">
                            <p>Lorem Ipsum Sit Date</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rellax circle-2" data-rellax-speed="-7">
                <img src="/images/symbols/circle-2.svg" />
            </div>
        </div>
        <div class="section py-5" id="sectionTutorial">
            <h1 class="mb-3 mb-md-5">How it works</h1>
            <div class="tutorial">
                <div class="tutorialText mb-2 text-center" style="max-width: 700px; width: 100%;">
                    <p>{{ __('index.how_it_works_text')}}</p>
                </div>
                <div class="row">
                    <div class="col-10 col-lg-5">
                        <div class="tutorialText mt-5 mt-lg-0 center text-center text-lg-left">
                            <h3>Lorem ipsum dolor sit</h3>
                            <p>Consectetur adipiscing elit. Sed purus lectus enim feugiat in quam facilisi molestie lorem. Rutrum fringilla interdum sit consectetur.</p>
                        </div>
                    </div>
                    <div class="col-10 col-lg-5">
                        <img class="tutImgRight" src="/images/frontpage/tutorial-1.png" />
                    </div>
                </div>
            </div>
            <div class="tutorial pb-5 d-none d-lg-block">
                <div class="row">
                    <div class="col-10 col-lg-5">
                        <img class="tutImgLeft" src="/images/frontpage/tutorial-2.png" />
                    </div>
                    <div class="col-10 col-lg-5 ">
                        <div class="tutorialText center mt-3 mt-lg-0 text-left">
                            <h3>Lorem ipsum dolor sit</h3>
                            <p>Consectetur adipiscing elit. Sed purus lectus enim feugiat in quam facilisi molestie lorem. Rutrum fringilla interdum sit consectetur.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tutorial pb-5 d-block d-lg-none">
                <div class="row">
                    <div class="col-10 ">
                        <div class="tutorialText text-center">
                            <h3>Lorem ipsum dolor sit</h3>
                            <p>Consectetur adipiscing elit. Sed purus lectus enim feugiat in quam facilisi molestie lorem. Rutrum fringilla interdum sit consectetur.</p>
                        </div>
                    </div>
                    <div class="col-10">
                        <img class="tutImgLeft" src="/images/frontpage/tutorial-2.png" />
                    </div>
                </div>
            </div>
            <div class="tutorial pt-5">
                <div class="row">
                    <div class="col-10">
                        <div class="tutorialText text-center mb-3">
                            <h3>Lorem ipsum dolor sit</h3>
                            <p>Consectetur adipiscing elit. Sed purus lectus enim feugiat in.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-10">
                        <img class="mb-5" src="/images/frontpage/tutorial-3.png" />
                        <a class="btn btn-primary d-table" href="/ycity/job/create">Create your Project</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="section py-5" id="sectionQuotes">
            <p id="quote">
                «{{ __('index.quote')}}»
                <span id="quoteTeller">{{ __('index.quote_teller')}}</span>
            </p>
            <div class="rellax triangle-2" data-rellax-speed="-2">
                <img src="/images/symbols/triangle.svg" />
            </div>
            <div class="rellax rectangle-2" data-rellax-speed="-5">
                <img src="/images/symbols/rectangle.svg" />
            </div>
            <div class="rellax triangle-3" data-rellax-speed="-6">
                <img src="/images/symbols/triangle.svg" />
            </div>
        </div>
        <!-- <div class="section py-5" id="sectionVideo">
            <div id="videoContainer">
                <img id="playButton" src="/images/symbols/triangle-play.svg" />
                <video id="player" playsinline controls data-poster="/images/frontpage/video-placeholder.jpg">
                    <source src="/videos/test.mov" type="video/mov" />
                </video>
            </div>
        </div> -->
        <!-- <div class="section pt-5" id="sectionBanner">
            <div class="banner banner-pink">
                <div class="title"><h1>Want to become a creator?</h1></div>
                <a class="button" href="/creator">View creator page</a>
            </div>
        </div> -->

        <div class="section py-5" id="sectionSafety">
            <div id="safetyContainer">
                <div class="container p-0">
                    <div class="row m-0">
                        <div class="mb-3 mb-md-0 col-10 col-md-5 p-0 pr-2">
                            <h2>{{ __('index.safety_title')}}</h2>
                            <p>{{ __('index.safety_text')}}</p>
                        </div>
                        <div class="col-10 col-md-5 p-0 pr-2">
                            <img class="m-auto d-table" src="/images/frontpage/safety.svg"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
    </div>
    @include("global.footer")
    @if(Auth::check())
        @include("global.notifications")
    @endif
    <script src="{{ mix('/js/app.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.js"></script>
    <script>

        $(document).ready(function(){
            $('#carousel').slick({
                slidesToShow: 3,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 10000,
                cssEase:'linear',
                infinite: true,
                focusOnSelect: false,
                variableWidth: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        slidesToShow: 3
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        slidesToShow: 1
                    }
                }]
            });

            var rellax = new Rellax('.rellax');
            var player = new Plyr('#player');
            $("button.plyr__control.plyr__control--overlaid").html("<h1>Play video</h1>");

            // Subkategorie der ausgewählten Kategorie zuweisen
            var catId = $('select[name="category"]').val();
            $('select[name="subcategory"] option').hide(); // Alle Optionen ausblenden
            $('select[name="subcategory"] option').removeAttr("selected"); // selected entfernen
            $('select[name="subcategory"] option[data-cat="' + catId + '"]').show(); // alle zugehörige Subkategorien einblenden
            $('select[name="subcategory"]').find('option[data-cat="' + catId + '"]:not(:hidden):eq(0)').attr("selected", "selected");  // erste eingeblendete Kategorie anzeigen

            // bei Änderung von Kategorie -> Subkategorie der ausgewählten Kategorie zuweisen
            $('select[name="category"]').on("change", function(e){
                var catId = $(this).val();
                $('select[name="subcategory"] option').hide(); // Alle Optionen ausblenden
                $('select[name="subcategory"] option').removeAttr("selected"); // selected entfernen
                $('select[name="subcategory"] option[data-cat="' + catId + '"]').show(); // alle zugehörige Subkategorien einblenden
                $('select[name="subcategory"]').find('option[data-cat="' + catId + '"]:not(:hidden):eq(0)').attr("selected", "selected");  // erste eingeblendete Kategorie anzeigen
            });

            $(".project").hover(function(){
                $(this).css("background-color", "yellow");
                $(".project:hover video").trigger('play');
                console.log($(".project:hover video"));
                }, function(){
                $(this).css("background-color", "pink");
                $(".project video").trigger('pause');

            });
        });



    </script>
    </body>
</html>
