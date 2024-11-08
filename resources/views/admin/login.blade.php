<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>BAI CIRLCE - Information System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Halaman Administrasi " name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset("assets")  }}/images/logoicon.png">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset("assets") }}/css/preloader.min.css" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset("assets") }}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset("assets") }}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset("assets") }}/css/app.min.css?v=3" id="app-style" rel="stylesheet" type="text/css" />
    @livewireStyles
</head>

<body>

<!-- <body data-layout="horizontal"> -->
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-xxl-3 col-lg-4 col-md-5">
                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class=" mb-md-5 text-center">
                                <a href="index.html" class="d-block auth-logo">
                                    <img src="{{ asset("assets/images/bottega-brown.png") }} " alt="" width="250">
                                </a>
                            </div>
                            <div class="auth-content ">

                                <div class="mt-5 text-center">
                                    @livewire('admin.login.form')
                                </div>
                            </div>
                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end auth full page content -->
            </div>
            <!-- end col -->
            <div class="col-xxl-9 col-lg-8 col-md-7">
                <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('{{asset('assets/images/DSC00609.jpg')}}') !important">
                    <div class=" "></div>
                    <ul class="bg-bubbles">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                    <!-- end bubble effect -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-xl-7">
                            <div class="p-0 p-sm-4 px-xl-0">
                                <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators carousel-indicators-rounded justify-content-start ms-0 mb-0">
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                    </div>
                                    <!-- end carouselIndicators -->

                                    <!-- end carousel-inner -->
                                </div>
                                <!-- end review carousel -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container fluid -->
</div>

@livewireScripts
<!-- JAVASCRIPT -->
<script src="{{ asset("assets") }}/libs/jquery/jquery.min.js"></script>
<script src="{{ asset("assets") }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset("assets") }}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{ asset("assets") }}/libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset("assets") }}/libs/node-waves/waves.min.js"></script>
<script src="{{ asset("assets") }}/libs/feather-icons/feather.min.js"></script>
<!-- pace js -->
<script src="{{ asset("assets") }}/libs/pace-js/pace.min.js"></script>
<!-- password addon init -->
<script src="{{ asset("assets") }}/js/pages/pass-addon.init.js"></script>

</body>

</html>
