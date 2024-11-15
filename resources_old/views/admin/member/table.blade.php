@extends('admin.template')

@section('konten')
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Member</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                    <li class="breadcrumb-item active">Member</li>
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
                                <h4 class="card-title">Member</h4>
                                <p class="card-title-desc">Data Member untuk mengelola data personalisasi member.
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-responsive-md m-t-40">
                                    <table id="jd-table"
                                           width="100%"
                                           data-urlaction="{{url('admin/member')}}"
                                           data-datasource = "{{url('admin/member/data-source')}}"
                                           class="display nowrap table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width:20px">No</th>
                                            <th style="width:100px">KODE</th>
                                            <th>NAMA LENGKAP</th>
                                            <th>LEVEL</th>
                                            <th>KONTAK</th>
                                            <th style="width: 30px">AKSI</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
{{--                @livewire('admin.member.form')--}}
        @include('admin.member.form')
@endsection

@push('script')
    <script src="{{asset('assets/js/controllers/member.js?v=2.3')}}"></script>
@endpush
