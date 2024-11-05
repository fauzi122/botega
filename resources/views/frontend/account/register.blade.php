@extends('frontend.widget.template')
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="breadcrumb-wrapper">


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
                    <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12 mx-auto">
                        <h1 class="page-title">Profile Member</h1>

                    </div>
                    @if ($errors->any())
                        <div id="error-alert" class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <script>
                            setTimeout(function(){
                                $('#error-alert').fadeOut('slow');
                            }, 3000);
                        </script>
                    @endif
                    <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12 mx-auto">
                        <!-- Login Form -->
                        <form action="{{url('profile-update')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="login-form">

                                <div class="row">
                                    <div class="col-md-2 col-12">
                                       <label style="font-weight: bold;">Member ID</label>
                                        <input type="text" placeholder="First Name" value="{{$profile->id_no}}" style="background-color: #e7e7e7" readonly>
                                    </div>
                                    <div class="col-md-5 col-12">
                                       <label style="font-weight: bold;">First Name</label>
                                        <input type="text" placeholder="First Name" value="{{$profile->first_name}}" name="first_name" required>

                                    </div>
                                    <div class="col-md-5 col-12">
                                       <label style="font-weight: bold;">Last Name</label>
                                        <input type="text" placeholder="Last Name" value="{{$profile->last_name}}" name="last_name" >
                                    </div>
                                    <div class="col-md-6">
                                       <label style="font-weight: bold;">Email Address*</label>
                                        <input type="email" placeholder="Email Address" name="email"   value="{{$profile->email}}" required>
                                    </div>
                                    <div class="col-md-6">
                                       <label style="font-weight: bold;">Nomor Handphone</label>
                                        <input type="number" placeholder="Nomor Handphone" name="nohp" value="{{$profile->hp}}" required>
                                    </div>

                                    <div class="col-md-4 col-12">
                                       <label style="font-weight: bold;">Kategori</label>
                                        <input type="text" placeholder="Last Name" style="background-color: #e7e7e7" value="{{$profile->sub_kategori}}" name="sub_kategori" >
                                    </div>
                                    <div class="col-md-4 col-12">
                                       <label style="font-weight: bold;">Jenis Kelamin</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="L" {{ $profile->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $profile->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label style="font-weight: bold;">Tanggal Lahir</label>
                                        <input type="date" placeholder="Tanggal Lahir" value="{{ $profile->birth_at}}" name="birth_at">
                                    </div>

                                    <div class="col-md-8 col-12">
                                       <label style="font-weight: bold;">Alamat</label>
                                        <input type="text" placeholder="Alamat Lengkap" value="{{$profile->home_addr}}" name="home_addr" >
                                    </div>
                                    <div class="col-md-2 col-12">
                                       <label style="font-weight: bold;">RT</label>
                                        <input type="number" placeholder="RT" value="{{$profile->rt}}" name="rt" >
                                    </div>
                                    <div class="col-md-2 col-12">
                                       <label style="font-weight: bold;">RW</label>
                                        <input type="number" placeholder="RW" value="{{$profile->rw}}" name="rw" >
                                    </div>

{{--                                    <div class="col-md-3 col-12">--}}
{{--                                        <label style="font-weight: bold;">Foto Profile</label>--}}
{{--                                        <div class="file-upload">--}}
{{--                                            @if($profile->foto_path && Storage::exists($profile->foto_path))--}}
{{--                                            <input type="file" class="dropify" data-default-file="{{url('profile-image/'.$profile->id.'.png')}}" name="foto_profile" accept="image/*" />--}}
{{--                                            @else--}}
{{--                                                <input type="file" class="dropify" data-max-file-size="3M"  name="foto_profile" accept="image/*" />--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="col-md-12 col-12">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <label style="font-weight: bold;">NPWP</label>
                                                <input type="number" placeholder="NPWP" value="{{$profile->npwp}}" name="npwp" >
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <label style="font-weight: bold;">NPPK</label>
                                                <input type="number" placeholder="NPPK" value="{{$profile->nppk}}" name="nppk" >
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <label style="font-weight: bold;">NIK</label>
                                                <input type="number" placeholder="NIK" value="{{$profile->nik}}" name="nik" >
                                            </div>


                                            <div class="col-md-12 col-12">
                                                <label style="font-weight: bold;">Alasan Perubahan *</label>
                                                <textarea class="form-control" name="reason_user" id="keterangan" cols="30" rows="3" required></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-12" style="margin-top: 50px; text-align: right;">
                                        <button class="theme-button" id="registerButton">Update Profile</button>
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
