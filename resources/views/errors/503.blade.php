<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--[if IE]><link rel="icon" href="/favicon.png"><![endif]-->
    <title>503</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700%7COpen+Sans&amp;display=swap"
        rel="stylesheet">
    <!--Bootstrap css-->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!--Magnific popup css-->
    <link rel="stylesheet" href="/css/magnific-popup.css">
    <!--Themify icon css-->
    <link rel="stylesheet" href="/css/themify-icons.css">
    <!--animated css-->
    <link rel="stylesheet" href="/css/animate.min.css">
    <!--Owl carousel css-->
    <link rel="stylesheet" href="/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/css/owl.theme.default.min.css">
    <!--custom css-->
    <link rel="stylesheet" href="/css/style.css">
    <!--responsive css-->
    <link rel="stylesheet" href="/css/responsive.css">
    <meta name="theme-color" content="#4DBA87">
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="appco-vue">
    <link rel="apple-touch-icon" href="/img/icons/apple-touch-icon-152x152.png">
    <link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#4DBA87">
    <meta name="msapplication-TileImage" content="/img/icons/msapplication-icon-144x144.png">
    <meta name="msapplication-TileColor" content="#000000">
    <style type="text/css">
        /**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
 */
        /*
 *  Owl Carousel - Core
 */
        .owl-carousel {
            display: none;
            width: 100%;
            -webkit-tap-highlight-color: transparent;
            /* position relative and z-index fix webkit rendering fonts issue */
            position: relative;
            z-index: 1;
        }

        .owl-carousel .owl-stage {
            position: relative;
            -ms-touch-action: pan-Y;
            touch-action: manipulation;
            -moz-backface-visibility: hidden;
            /* fix firefox animation glitch */
        }

        .owl-carousel .owl-stage:after {
            content: ".";
            display: block;
            clear: both;
            visibility: hidden;
            line-height: 0;
            height: 0;
        }

        .owl-carousel .owl-stage-outer {
            position: relative;
            overflow: hidden;
            /* fix for flashing background */
            -webkit-transform: translate3d(0px, 0px, 0px);
        }

        .owl-carousel .owl-wrapper,
        .owl-carousel .owl-item {
            -webkit-backface-visibility: hidden;
            -moz-backface-visibility: hidden;
            -ms-backface-visibility: hidden;
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
        }

        .owl-carousel .owl-item {
            position: relative;
            min-height: 1px;
            float: left;
            -webkit-backface-visibility: hidden;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }

        .owl-carousel .owl-item img {
            display: block;
            width: 100%;
        }

        .owl-carousel .owl-nav.disabled,
        .owl-carousel .owl-dots.disabled {
            display: none;
        }

        .owl-carousel .owl-nav .owl-prev,
        .owl-carousel .owl-nav .owl-next,
        .owl-carousel .owl-dot {
            cursor: pointer;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .owl-carousel .owl-nav button.owl-prev,
        .owl-carousel .owl-nav button.owl-next,
        .owl-carousel button.owl-dot {
            background: none;
            color: inherit;
            border: none;
            padding: 0 !important;
            font: inherit;
        }

        .owl-carousel.owl-loaded {
            display: block;
        }

        .owl-carousel.owl-loading {
            opacity: 0;
            display: block;
        }

        .owl-carousel.owl-hidden {
            opacity: 0;
        }

        .owl-carousel.owl-refresh .owl-item {
            visibility: hidden;
        }

        .owl-carousel.owl-drag .owl-item {
            -ms-touch-action: pan-y;
            touch-action: pan-y;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .owl-carousel.owl-grab {
            cursor: move;
            cursor: grab;
        }

        .owl-carousel.owl-rtl {
            direction: rtl;
        }

        .owl-carousel.owl-rtl .owl-item {
            float: right;
        }

        /* No Js */
        .no-js .owl-carousel {
            display: block;
        }

        /*
 *  Owl Carousel - Animate Plugin
 */
        .owl-carousel .animated {
            animation-duration: 1000ms;
            animation-fill-mode: both;
        }

        .owl-carousel .owl-animated-in {
            z-index: 0;
        }

        .owl-carousel .owl-animated-out {
            z-index: 1;
        }

        .owl-carousel .fadeOut {
            animation-name: fadeOut;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        /*
 * 	Owl Carousel - Auto Height Plugin
 */
        .owl-height {
            transition: height 500ms ease-in-out;
        }

        /*
 * 	Owl Carousel - Lazy Load Plugin
 */
        .owl-carousel .owl-item {
            /**
   This is introduced due to a bug in IE11 where lazy loading combined with autoheight plugin causes a wrong
   calculation of the height of the owl-item that breaks page layouts
  */
        }

        .owl-carousel .owl-item .owl-lazy {
            opacity: 0;
            transition: opacity 400ms ease;
        }

        .owl-carousel .owl-item .owl-lazy[src^=""],
        .owl-carousel .owl-item .owl-lazy:not([src]) {
            max-height: 0;
        }

        .owl-carousel .owl-item img.owl-lazy {
            transform-style: preserve-3d;
        }

        /*
 * 	Owl Carousel - Video Plugin
 */
        .owl-carousel .owl-video-wrapper {
            position: relative;
            height: 100%;
            background: #000;
        }

        .owl-carousel .owl-video-play-icon {
            position: absolute;
            height: 80px;
            width: 80px;
            left: 50%;
            top: 50%;
            margin-left: -40px;
            margin-top: -40px;
            background: url(4a37f8008959c75f619bf0a3a4e2d7a2.png) no-repeat;
            cursor: pointer;
            z-index: 1;
            -webkit-backface-visibility: hidden;
            transition: transform 100ms ease;
        }

        .owl-carousel .owl-video-play-icon:hover {
            -ms-transform: scale(1.3, 1.3);
            transform: scale(1.3, 1.3);
        }

        .owl-carousel .owl-video-playing .owl-video-tn,
        .owl-carousel .owl-video-playing .owl-video-play-icon {
            display: none;
        }

        .owl-carousel .owl-video-tn {
            opacity: 0;
            height: 100%;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: contain;
            transition: opacity 400ms ease;
        }

        .owl-carousel .owl-video-frame {
            position: relative;
            z-index: 1;
            height: 100%;
            width: 100%;
        }
    </style>
    <style type="text/css">
        /**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
 */
        /*
 * 	Default theme - Owl Carousel CSS File
 */
        .owl-theme .owl-nav {
            margin-top: 10px;
            text-align: center;
            -webkit-tap-highlight-color: transparent;
        }

        .owl-theme .owl-nav [class*='owl-'] {
            color: #FFF;
            font-size: 14px;
            margin: 5px;
            padding: 4px 7px;
            background: #D6D6D6;
            display: inline-block;
            cursor: pointer;
            border-radius: 3px;
        }

        .owl-theme .owl-nav [class*='owl-']:hover {
            background: #869791;
            color: #FFF;
            text-decoration: none;
        }

        .owl-theme .owl-nav .disabled {
            opacity: 0.5;
            cursor: default;
        }

        .owl-theme .owl-nav.disabled+.owl-dots {
            margin-top: 10px;
        }

        .owl-theme .owl-dots {
            text-align: center;
            -webkit-tap-highlight-color: transparent;
        }

        .owl-theme .owl-dots .owl-dot {
            display: inline-block;
            zoom: 1;
            *display: inline;
        }

        .owl-theme .owl-dots .owl-dot span {
            width: 10px;
            height: 10px;
            margin: 5px 7px;
            background: #D6D6D6;
            display: block;
            -webkit-backface-visibility: visible;
            transition: opacity 200ms ease;
            border-radius: 30px;
        }

        .owl-theme .owl-dots .owl-dot.active span,
        .owl-theme .owl-dots .owl-dot:hover span {
            background: #869791;
        }
    </style>
</head>

<body><noscript><strong>
            We're sorry but appco-vue doesn't work properly without JavaScript enabled. Please enable it to
            continue.</strong></noscript>
    <div id="app">
        <div data-v-43b07a04="" class="main">
            <section data-v-43b07a04="" class="hero-section ptb-100 background-img full-screen banner-1-bg">
                <div data-v-43b07a04="" class="container">
                    <div data-v-43b07a04="" class="row align-items-center justify-content-center">
                        <div data-v-43b07a04="" class="col-md-9 col-lg-7">
                            <div data-v-8913de20="" class="hero-content-left text-white text-center">
                                <h1 data-v-8913de20="" class="text-white"> بزودی برمیگردیم</h1>
                                <p data-v-8913de20="" class="lead"> ما در حال بروز رسانی هستیم.لطفا صبور باشید </p>

                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                        role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"
                                        style="width: 50%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    {{-- <script src="/js/chunk-vendors.js"></script> --}}
    <script src="/js/app.js"></script>
</body>

</html>
