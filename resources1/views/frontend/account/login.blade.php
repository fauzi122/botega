@extends('frontend.widget.template')
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="breadcrumb-wrapper">
                        <h2 class="page-title">Login Account</h2>

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
                        <form action="{{url('login/validasi')}}" method="post">
                            @csrf
                            <div class="login-form login-form--extra-space">
                                <h4 class="login-title">Login</h4>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label>Email Address*</label>
                                        @if ($errors->has('email'))
                                            <span class="text-danger small">{{ $errors->first('email') }}</span>
                                        @endif
                                        <input type="email" name="email" placeholder="Email Address">

                                    </div>
                                    <div class="col-12">
                                        <label>Password*</label>
                                        @if ($errors->has('password'))
                                            <span class="text-danger small">{{ $errors->first('password') }}</span>
                                        @endif
                                        <input type="password" name="password" placeholder="Password">
                                    </div>
                                    <div class="col-md-8">

                                    </div>

                                    <div class="col-md-4 mt-10 text-start text-md-end">
                                        <a href="{{url('login/forget')}}" class="forget-pass-link"> Lupa
                                            Password?</a>
                                    </div>

                                    <div class="col-md-12">
                                        <button class="theme-button">Login</button>
                                    </div>

                                    <div class="col-md-12 mt-20 text-start text-md-start" style="margin-top: 20px">
                                      Belum punya akun ?  <a href="{{url('login/register')}}" class="forget-pass-link"><b>Register</b> </a>
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
