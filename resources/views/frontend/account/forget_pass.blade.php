@extends('frontend.widget.template')
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="breadcrumb-wrapper">
                        <h2 class="page-title">Ubah Password</h2>

                    </div>

                    <!--=======  End of breadcrumb wrapper  =======-->
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <!--=======  my account wrapper  =======-->

        <div class="myaccount-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xs-12 col-lg-6 mx-auto">
                        <!-- Login Form -->
                        <form action="{{url('login/resetpasswordacc')}}" method="post">
                            @csrf
                            <div class="login-form">

                                <div class="row">
                                    <input type="hidden" name="token" value="{{$token}}">

                                    <div class="col-md-12">
                                        <label>Password</label>
                                        <input type="password" placeholder="Password" name="password" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Confirm Password</label>
                                        <input type="password" placeholder="Confirm Password" name="confirm_password" id="confirmPassword">
                                        <code id="passwordMismatch" style="color: red; display: none;">Password konfirmasi tidak sama</code>
                                    </div>
                                    <div class="col-12">
                                        <button class="theme-button" id="registerButton" disabled>Ubah Password</button>
                                    </div>
                                    <div class="col-md-12 mt-20 text-start text-md-start" style="margin-top: 20px">
                                        Sudah punya akun ?  <a href="{{url('login')}}" class="forget-pass-link"><b>Login</b> </a>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!--=======  End of my account wrapper  =======-->
    </div>

@endsection

@section('script')
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
@endsection
