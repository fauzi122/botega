<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8"/>
    <title>Login | Bottega & Artisan </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to Bottega Artisan." />
    <meta name="keywords" content="Bottega Artisan,Bottega,Artisan,Italica,Exotica,Duraslab,Tiles,Interior,Rohl,Moen,Pullcast,Sun Valley Bronze,Lasvit,Sans Souci,Assa Abloy,Gaggenau,Bosch" />
    <meta content="Halaman Administrasi " name="description"/>
    <meta content="" name="author"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset("assets")  }}/images/logoicon.png">

    <!-- preloader css -->
    <link rel="stylesheet" href="{{ asset("assets") }}/css/preloader.min.css" type="text/css"/>

    <!-- Bootstrap Css -->
    <link href="{{ asset("assets") }}/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="{{ asset("assets") }}/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{ asset("assets") }}/css/app.min.css?v=3" id="app-style" rel="stylesheet" type="text/css"/>

</head>

<body>

<!-- <body data-layout="horizontal"> -->
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-xxl-5 col-lg-4 col-md-5">
                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">
                            <div class="mb-4 mb-md-5 text-center">
                                <a href="" class="d-block auth-logo">
                                    <img src="{{ asset("assets") }}/images/bottega-brown.png" alt="" height="80">
                                </a>
                            </div>
                            <div class="auth-content my-auto">
                                <div class="text-center">
                                    <h5 class="mb-0">Login Account !</h5>
                                    <p class="text-muted mt-2">Sign in to continue to bottega &artisan.</p>
                                </div>
                                <form class="mt-4 pt-2" method="post" action="{{ url('login/validasi')  }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="username"
                                               placeholder="Enter Email">
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <label class="form-label">Password</label>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="">
                                                    <a href="{{ url("login/forget")  }}" class="text-muted">Forgot
                                                        password?</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" name="password" class="form-control"
                                                   placeholder="Enter password" aria-label="Password"
                                                   aria-describedby="password-addon">
                                            <button class="btn btn-light shadow-none ms-0" type="button"
                                                    id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">
                                            {{--                                            <div class="form-check">--}}
                                            {{--                                                <input class="form-check-input" type="checkbox" id="remember-check">--}}
                                            {{--                                                <label class="form-check-label" for="remember-check">--}}
                                            {{--                                                    Remember me--}}
                                            {{--                                                </label>--}}
                                            {{--                                            </div>--}}
                                        </div>

                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log
                                            In
                                        </button>
                                    </div>
                                </form>


                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">Don't have an account ? <a href="{{url('login/register')}}"
                                                                                          class="text-primary fw-semibold">
                                            Signup now </a></p>
                                </div>
                            </div>
                            <div class="mt-4 mt-md-5 text-center">
                                <p class="mb-0">©
                                    <script>document.write(new Date().getFullYear())</script> | Bottega & Artisan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end auth full page content -->
            </div>
            <!-- end col -->
            <div class="col-xxl-7 col-lg-8 col-md-7">
                <div class="auth-bg pt-md-5 p-4 d-flex">
                    <div class="bg-overlay bg-primary"></div>
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
                                    <div
                                        class="carousel-indicators carousel-indicators-rounded justify-content-start ms-0 mb-0">
                                        <button type="button" data-bs-target="#reviewcarouselIndicators"
                                                data-bs-slide-to="0" class="active" aria-current="true"
                                                aria-label="Slide 1"></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators"
                                                data-bs-slide-to="1" aria-label="Slide 2"></button>
                                        <button type="button" data-bs-target="#reviewcarouselIndicators"
                                                data-bs-slide-to="2" aria-label="Slide 3"></button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
        });
    </script>
@endif
</body>

</html>


