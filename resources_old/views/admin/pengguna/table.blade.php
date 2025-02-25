@extends('admin.template')

@section('konten')
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">System</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">System</a></li>
                                    <li class="breadcrumb-item active">Pengguna</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Pengguna</h4>
                                <p class="card-title-desc">
                                   Kelola data pengguna disini. Mulai menambahkan maupun reset sandi pengguna
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-responsive-md m-t-40">
                                    <table id="jd-table"
                                           width="100%"
                                           data-urlaction="{{url('admin/pengguna')}}"
                                           data-datasource = "{{url('admin/pengguna/data-source')}}"
                                           class="display nowrap table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>KATEGORI</th>
                                            <th>NIK/NPWP</th>
                                            <th>KONTAK</th>
                                            <th>ROLE</th>
                                            <th style="width: 100px">AKSI</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

                @livewire('admin.pengguna.form')

@endsection

@push('script')
    <script src="{{asset('assets/js/controllers/pengguna.js')}}"></script>
@endpush
