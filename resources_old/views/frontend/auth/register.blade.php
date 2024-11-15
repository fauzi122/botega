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
    <link href="{{ asset("assets") }}/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>

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
                                    <h5 class="mb-0">Register Account !</h5>
                                    <p class="text-muted mt-2">Sign in to continue to Minia.</p>
                                </div>
                                <form class="mt-4 pt-2" method="post" action="{{ url('login/registeracc')  }}">
                                    @csrf
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <label>First Name*</label>
                                                <input type="text" placeholder="First Name"  class="form-control" name="first_name" required>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" placeholder="Last Name" name="last_name" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email Address*</label>
                                        <input type="email" class="form-control" placeholder="Email Address" name="email" required>

                                    </div>
                                    <div class="mb-3">
                                        <label>No. Handphone*</label>
                                        <input type="number" class="form-control" placeholder="No. Handphone" name="nohp" required>

                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Password</label>
                                                <input type="password" class="form-control" placeholder="Password" name="password" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Confirm Password</label>
                                                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirmPassword">
                                                <code id="passwordMismatch" style="color: red; display: none;">Password konfirmasi tidak sama</code>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-4">
                                        <div class="col">

                                        </div>

                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100 waves-effect waves-light" id="registerButton"  type="submit">Register
                                        </button>
                                    </div>
                                </form>


                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">Already have an account ? <a href="{{url('login')}}"
                                                                                          class="text-primary fw-semibold">
                                            Login </a></p>
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
<script>
    $(document).ready(function() {
        // Memberikan fungsi pada event input pada kolom confirm password
        $('#confirmPassword').on('input', function() {
            var password = $('input[name="password"]').val();
            var confirmPassword = $(this).val();

            // Memeriksa apakah password dan confirm password cocok
            if (password !== confirmPassword) {
                $('#passwordMismatch').css('display', 'inline'); // Menampilkan pesan kesalahan
                $('#registerButton').prop('disabled', true); // Menonaktifkan tombol submit
            } else {
                $('#passwordMismatch').css('display', 'none'); // Sembunyikan pesan kesalahan jika cocok
                $('#registerButton').prop('disabled', false); // Mengaktifkan tombol submit
            }
        });
    });
</script>

</body>

</html>


