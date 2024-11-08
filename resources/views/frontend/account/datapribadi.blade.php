@php use Carbon\Carbon; @endphp
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
                        <h1 class="page-title">Profile Member <a href="{{url('profile-data')}}" class="btn btn-warning"
                                                                 style="border-radius: 40px; background-color: saddlebrown; color: white">
                                <i class="fa fa-edit"></i>
                                Ajukan Perubahan
                            </a></h1>
                    </div>

                    <div class="col-sm-4 col-md-4 col-xs-4 col-lg-4 mx-auto">
                        <!-- Login Form -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="ibox-title pdd-header">
                                        <h4 class="si-text si-text__heading-5 si-text--bold m-0">
                                            Profile
                                        </h4>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="file-upload">
                                                <img src="{{url('profile-image/'.$profile->id.'.png')}}"
                                                     class="img-fluid" alt=""
                                                     style="border-radius: 20px; width: 100%; height: 300px; object-fit: cover"
                                                     onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12" style="margin-top: 20px;">
                                            <center>
                                                <button type="button" class="btn btn-success btn-sm" style="width: 100%; background-color: saddlebrown;border-color: saddlebrown" data-bs-toggle="modal"
                                                        data-bs-target="#uploadFotoModal">
                                                    <i class="fa fa-upload"></i> Upload Foto
                                                </button>
                                            </center>


                                            <!-- Modal -->
                                            <div class="modal fade" id="uploadFotoModal" tabindex="-1"
                                                 aria-labelledby="uploadFotoModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="uploadFotoModalLabel">Upload
                                                                Foto</h5>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{url('upload-fotoprofile')}}" method="post"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="file" class="dropify" name="foto"
                                                                       id="foto" accept="image/*">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary" style="background-color: saddlebrown; border-color: saddlebrown">Simpan
                                                                    Foto
                                                                </button>

                                                                <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-12" style="margin-top: 25px">
                                            <table class="table">
                                                <tr>
                                                    <td>No. Member</td>
                                                    <td>:</td>
                                                    <td>{{$profile->id_no}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Lengkap</td>
                                                    <td>:</td>
                                                    <td>{{$profile->first_name}} {{$profile->last_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Kelamin</td>
                                                    <td>:</td>
                                                    <td>{{ $profile->gender === 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Lahir</td>
                                                    <td>:</td>
                                                    <td>{{ Carbon::parse($profile->birth_at)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>

                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 mx-auto">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="ibox-title pdd-header">
                                        <h4 class="si-text si-text__heading-5 si-text--bold m-0">
                                            Alamat dan Kontak
                                        </h4>
                                    </div>
                                    <hr>
                                    <div class="row">

                                        <div class="col-md-12 col-12">
                                            <table class="table">
                                                <tr>
                                                    <td>Email</td>
                                                    <td>:</td>
                                                    <td>{{$profile->email}}</td>
                                                </tr>
                                                <tr>
                                                    <td>No Handphone</td>
                                                    <td>:</td>
                                                    <td>{{$profile->hp}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Alamat</td>
                                                    <td>:</td>
                                                    <td>{{$profile->home_addr}}</td>
                                                </tr>

                                                <tr>
                                                    <td>RT</td>
                                                    <td>:</td>
                                                    <td>{{$profile->rt}}</td>
                                                </tr>
                                                <tr>
                                                    <td>RW</td>
                                                    <td>:</td>
                                                    <td>{{$profile->rw}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Fax</td>
                                                    <td>:</td>
                                                    <td>{{$profile->zip_code}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Web</td>
                                                    <td>:</td>
                                                    <td>{{$profile->zip_code}}</td>
                                                </tr>

                                            </table>


                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="ibox-title pdd-header">
                                        <h4 class="si-text si-text__heading-5 si-text--bold m-0">
                                            Lain-lain
                                        </h4>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <table class="table">
                                                <tr>
                                                    <td>NIK</td>
                                                    <td>:</td>
                                                    <td>{{$profile->nik}}</td>
                                                </tr>
                                                <tr>
                                                    <td>NPWP</td>
                                                    <td>:</td>
                                                    <td>{{$profile->npwp}}</td>
                                                </tr>
                                                <tr>
                                                    <td>NPPKP</td>
                                                    <td>:</td>
                                                    <td>{{ $profile->nppkp}}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-lg-12 mt-4">
                        <div class="login-form">
                            <div class="ibox-title pdd-header">
                                <h4 class="si-text si-text__heading-5 si-text--bold m-0">
                                    Riwayat Pengajuan
                                </h4>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <table class="cart-table">
                                        <thead>
                                        <tr>

                                            <th>Diajukan</th>
                                            <th>Alasan Ajuan</th>
                                            <th>Status</th>
{{--                                            <th>Tindakan</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($riwayat as $r)
                                            <tr>

                                                <td>{{ Carbon::parse($r->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY H:i:s') }}</td>

                                                <td>{{$r->reason_user}}</td>
                                                <td>
                                                    @if($r->status == 'Submited')
                                                        <span class="stock-stat-message"
                                                              style="color: orange; border: 1px solid orange;">Submited</span>
                                                    @elseif($r->status == 'Rejected')
                                                        <span
                                                            class="stock-stat-message stock-stat-message--out-of-stock">Reject</span>
                                                    @else
                                                        <span class="stock-stat-message"
                                                              >Accept</span>
                                                    @endif
                                                </td>

{{--                                                <td>--}}
{{--                                                    <button class="btn btn-primary btn-sm"><i--}}
{{--                                                            class="fa fa-check-circle"></i></button>--}}
{{--                                                </td>--}}
                                            </tr>
                                        @endforeach


                                        </tbody>


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--=======  End of my account wrapper  =======-->
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Memberikan fungsi pada event input pada kolom confirm password
            $('#confirmPassword').on('input', function () {
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
