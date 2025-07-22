@php
    use App\Models\User;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="rtl" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

    <head>

        <meta charset="utf-8">
        <title> 404 </title>

        <!-- Favicon -->
        {{-- <link rel="shortcut icon" type="image/x-icon" href="/assets/imgs/theme/favicon.svg"> --}}
        @if (User::first() && User::first()->image)
            <link rel="shortcut icon" href="{{asset('storage/'.User::find(1)->image->url)}}" type="image/x-icon">
        @endif
        <!-- Template CSS -->
        <link rel="stylesheet" href="/assets/css/main.css?v=6.0">
    </head>

    <body><div class="body-overlay-1"></div>

        <!--End header-->
        <main class="main page-404">
            <div class="page-content pt-150 pb-150">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-10 col-md-12 m-auto text-center">
                            <p class="mb-20"><img src="assets/imgs/page/page-404.png" alt="" class="hover-up"></p>
                            <h1 class="display-2 mb-30">صفحه 404</h1>
                            <p class="font-lg text-grey-700 mb-30">
                                صفحه ای که به دنبال آن میگردید یا وجود ندارد یا به طور موقت از دسترس خارج شده است.<br>

                            </p>

                            <a class="btn btn-default submit-auto-width font-xs hover-up mt-30" href="/"><i class="fi-rs-home mr-5"></i> صفحه اصلی</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>


        <!-- Vendor JS-->
        <script src="/assets/js/vendor/modernizr-3.6.0.min.js"></script>
        <script src="/assets/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="/assets/js/vendor/jquery-migrate-3.3.0.min.js"></script>
        <script src="/assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/plugins/slick.js"></script>
        <script src="/assets/js/plugins/jquery.syotimer.min.js"></script>
        <script src="/assets/js/plugins/wow.js"></script>
        <script src="/assets/js/plugins/perfect-scrollbar.js"></script>
        <script src="/assets/js/plugins/magnific-popup.js"></script>
        <script src="/assets/js/plugins/select2.min.js"></script>
        <script src="/assets/js/plugins/waypoints.js"></script>
        <script src="/assets/js/plugins/counterup.js"></script>
        <script src="/assets/js/plugins/jquery.countdown.min.js"></script>
        <script src="/assets/js/plugins/images-loaded.js"></script>
        <script src="/assets/js/plugins/isotope.js"></script>
        <script src="/assets/js/plugins/scrollup.js"></script>
        <script src="/assets/js/plugins/jquery.vticker-min.js"></script>
        <script src="/assets/js/plugins/jquery.theia.sticky.js"></script>
        <script src="/assets/js/plugins/jquery.elevatezoom.js"></script>
        <!-- Template  JS -->
        <script src="/assets/js/main.js?v=6.0"></script><a id="scrollUp" href="#top" style="display: none; position: fixed; z-index: 2147483647;"><i class="fi-rs-arrow-small-up"></i></a>
        <script src="/assets/js/shop.js?v=6.0"></script>


</body></html>
