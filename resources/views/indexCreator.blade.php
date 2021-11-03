<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="{{ asset('js/jquery-min.js') }}"></script>
        @include("global.head")
        <link href="{{ asset('css/frontpage.css?v=').time()}}" rel="stylesheet">
        <title>{{ config('app.name', 'Laravel') }} | {{ __('index.creator_meta_title')}}</title>
        <meta name="description" content="{{ __('index.creator_meta_text')}}">
        <!-- Og Infos-->
        <meta property="og:title" content="{{ __('index.creator_meta_title')}}" />
        <meta property="og:description" content="{{ __('index.creator_meta_text')}}">
        <meta name="title" content="Be the creativity of tomorrow">
        <meta property="og:image" content="/favicon.png">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/gh/dixonandmoe/rellax@master/rellax.min.js"></script>
        <link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.6.8/plyr.polyfilled.js"></script>
        <style>
            #sectionClients .row {
                display: flex;
                flex-wrap: wrap;
                padding: 0 4px;
            }

            /* Create four equal columns that sits next to each other */
            #sectionClients .column {
                flex: 25%;
                max-width: 25%;
                padding: 0 4px;
                border-radius: 16px;
                background: #F8F8F8;
            }

            #sectionClients .column img {
                margin-top: 8px;
                vertical-align: middle;
                width: 100%;
                border-radius: 16px;
            }

            /* Responsive layout - makes a two column-layout instead of four columns */
            @media screen and (max-width: 800px) {
                #sectionClients .column {
                    flex: 50%;
                    max-width: 50%;
                }
            }

            /* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
            @media screen and (max-width: 600px) {
                #sectionClients .column {
                    flex: 100%;
                    max-width: 100%;
                }
            }

            #bannerCreatorTitle {
                position: absolute;
                top: 30%;
                right: 15%;
                transform: translateY(-50%);
                max-width: 911px;
                font-size: 128px;
                line-height: 128px;
                font-feature-settings: "ss01" on;
                text-align: right;
                z-index: 1;
                letter-spacing: 3px;
            }

            #bannerCreatorTitle #bannerTitle {
                position: unset;
                top: 30%;
                right: 15%;
                transform: none;
                clear: both;
            }
            #sectionHeader{
                height: 80vh;
            }
            #bannerText{
                position: absolute;
                bottom: 0;
            }
        </style>
    </head>
    <body>
    @include("global.navi")
    <div class="mainContent">
        <div class="section mb-5" id="sectionHeader">
            <div id="bannerCreatorTitle">
                <img class="mt-3 float-right" style="width: 110%;max-width: 45rem;" src="/images/frontpage/be-the-creator.svg"/>
            </div>
            <p id="bannerText">{{ __('index.header_text')}}</p>
            <div class="rellax circle" data-rellax-speed="4">
                <img src="/images/symbols/circle.svg" />
            </div>
            <div class="rellax triangle" data-rellax-speed="-3">
                <img src="/images/symbols/triangle.svg" />
            </div>
            <div class="rellax rectangle" data-rellax-speed="-6">
                <img src="/images/symbols/rectangle.svg" />
            </div>
        </div>
        <div class="section mt-5 pt-5">
            <h2 class="sectionTitle">Are you an outstanding...</h2>
        </div>
        <!-- <div id="sectionSlider" class="section pt-2 pb-3">
            <div id="carousel">
                <div id="carousel">
                    <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/search?category=Video'"><div class="carousel-element-inner"><img src="/images/icons/category/film.svg" />Video</div></div></div>
                    <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/search?category=Fotografie'"><div class="carousel-element-inner"><img src="/images/icons/category/camera.svg" />Photo</div></div></div>
                    <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/search?category=Video'"><div class="carousel-element-inner"><img src="/images/icons/category/film.svg" />Video</div></div></div>
                    <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/search?category=Social Media'"><div class="carousel-element-inner"><img src="/images/icons/category/smartphone.svg" />Social Media</div></div></div>
                    <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/search?category=Audio'"><div class="carousel-element-inner"><img src="/images/icons/category/headphones.svg" />Audio</div></div></div>
                    <div><div class="carousel-element" onclick="javascript:location.href='/ycity/job/search?category=Design'"><div class="carousel-element-inner"><img src="/images/icons/category/pen-tool.svg" />Design</div></div></div>
                </div>
            </div>
        </div> -->
        <div id="sectionSlider" class="section py-5">
            <div id="carousel">
                <div><div class="carousel-element"><div class="carousel-element-inner"><img src="/images/icons/category/film.svg" />Video</div></div></div>
                <div><div class="carousel-element"><div class="carousel-element-inner"><img src="/images/icons/category/camera.svg" />Photo</div></div></div>
                <div><div class="carousel-element"><div class="carousel-element-inner"><img src="/images/icons/category/film.svg" />Video</div></div></div>
                <div><div class="carousel-element"><div class="carousel-element-inner"><img src="/images/icons/category/smartphone.svg" />Social Media</div></div></div>
                <div><div class="carousel-element"><div class="carousel-element-inner"><img src="/images/icons/category/headphones.svg" />Audio</div></div></div>
                <div><div class="carousel-element"><div class="carousel-element-inner"><img src="/images/icons/category/pen-tool.svg" />Design</div></div></div>
            </div>
        </div>
        <div class="section mb-5 pb-5">
            <p class="sectionText">Great. Because we’re looking for people just like you. I’m sure Nico can come up with a better text than this.</p>
        </div>
        <div class="section mt-5 pt-5 pb-3">
            <h2 class="sectionTitle">Work together with great Clients like...</h2>
        </div>
        <!-- <div class="section pb-5" id="sectionClients">
            <div class="container p-0">
                <div class="row m-0">
                    <div class="row">
                        <div class="column">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                        </div>
                        <div class="column">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                        </div>
                        <div class="column">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                        </div>
                        <div class="column">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                            <img src="/images/frontpage/placeholder.png">
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="rellax circle-2" data-rellax-speed="-7">
                <img src="/images/symbols/circle-2.svg" />
            </div>
        </div>
        <!-- <div class="section py-5" id="sectionVideo">
            <div class="rellax circle-3" data-rellax-speed="-3">
                <img src="/images/symbols/circle.svg" />
            </div>
            <div id="videoContainer">
                <img id="playButton" src="/images/symbols/triangle-play.svg" />
                <video id="player" playsinline controls data-poster="/images/frontpage/video-placeholder.jpg">
                    <source src="/videos/test.mov" type="video/mov" />
                </video>
            </div>
        </div> -->
        <div class="section pt-5" id="sectionBanner">
            <div class="banner banner-pink">
                <div class="title"><h1>Have a project for creators?</h1></div>
                <a class="button" href="/creator">View client page</a>
            </div>
        </div>
        <div class="section py-5" id="sectionTutorial">
            <h1 class="mb-3 mb-md-5">How it works</h1>
            <div class="tutorialText mb-2 text-center" style="max-width: 700px; width: 100%;">
                <p>{{ __('index.how_it_works_text')}}</p>
            </div>
            <div class="tutorial">
                <div class="row">
                    <div class="col-10 col-lg-5">
                        <div class="tutorialText mt-5 mt-lg-0  center text-center text-lg-left">
                            <h3>Lorem ipsum dolor sit</h3>
                            <p>Consectetur adipiscing elit. Sed purus lectus enim feugiat in quam facilisi molestie lorem. Rutrum fringilla interdum sit consectetur.</p>
                        </div>
                    </div>
                    <div class="col-10 col-lg-5">
                        <img class="tutImgRight" src="/images/frontpage/tutorial-c-1.png" />
                    </div>
                </div>
            </div>
            <div class="tutorial pb-5 d-none d-lg-block">
                <div class="row">
                    <div class="col-10 col-lg-5">
                        <img class="tutImgLeft" src="/images/frontpage/tutorial-c-2.png" />
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
                        <img class="tutImgLeft" src="/images/frontpage/tutorial-c-2.png" />
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
                        <img class="mb-5" src="/images/frontpage/tutorial-c-3.png" />
                        <a class="btn btn-primary d-table m-auto" href="/ycity/job/search">Search projects</a>
                    </div>
                </div>
            </div>
        </div>
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
            $("button.plyr__control.plyr__control--overlaid").html("<h1>Play video</h1>")
        });

    </script>
    </body>
</html>
