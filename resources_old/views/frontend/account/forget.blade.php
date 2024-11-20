@extends('frontend.widget.template')
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="breadcrumb-wrapper">
                        <h2 class="page-title">Reset Account</h2>

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
                        <form action="{{url('login/forgetacc')}}" method="post">
                            @csrf
                            <div class="login-form login-form--extra-space">
                                <h4 class="login-title">Reset Password</h4>

                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <label>Email Address*</label>
                                        <input type="email" name="email" placeholder="Email Address">
                                    </div>
                                    <div class="col-md-12">
                                        <button class="theme-button">Kirim Email</button>
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
