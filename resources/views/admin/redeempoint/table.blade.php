@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Redeem Point</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Reward</a></li>
                    <li class="breadcrumb-item active">Redeem Point</li>
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
                <h4 class="card-title">Redeem Point</h4>
                <p class="card-title-desc">
                    Redeem Point ini untuk menyetujui pengajuan penukaran poin dengan reward yang ditawarkan.
                </p>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs-custom card-header-tabs border-top mt-4" id="pills-tab" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link px-3 active" data-bs-toggle="tab" href="#tab" role="tab" aria-selected="false" tabindex="-1">Pengajuan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-proses" role="tab" aria-selected="false" tabindex="-1">Proses</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-acc" role="tab" aria-selected="false" tabindex="-1">Disetujui</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-tolak" role="tab" aria-selected="false" tabindex="-1">Ditolak</a>
                    </li>
                </ul>
                <div class="tab-content" style="padding-top: 50px">

                    <div class="tab-pane active" role="tabpanel" id="tab">
                        <div class="table-responsive table-responsive-md m-t-40">
                            <table id="jd-table"
                                width="100%"
                                data-urlaction="{{url('admin/redeem')}}"
                                data-datasource="{{url('admin/redeem/data-source')}}"
                                class="display nowrap table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="80px">NO</th>
                                        <th>MEMBER</th>
                                        <th>REWARD</th>
                                        <th>POINT</th>
                                        <th>PENGAJUAN</th>
                                        <th>SETUJUI</th>
                                        <th style="width:100px">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="tab-proses">
                        <div class="table-responsive table-responsive-md m-t-40">
                            <table id="jd-table-proses"
                                width="100%"
                                data-urlaction="{{url('admin/redeem')}}"
                                data-datasource="{{url('admin/redeem/data-source-proses')}}"
                                class="display nowrap table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="80px">NO</th>
                                        <th>MEMBER</th>
                                        <th>REWARD</th>
                                        <th>POINT</th>
                                        <th>PENGAJUAN</th>
                                        <th>SETUJUI</th>
                                        <th style="width:100px">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="tab-acc">
                        <div class="table-responsive table-responsive-md m-t-40">
                            <table id="jd-table-acc"
                                width="100%"
                                data-urlaction="{{url('admin/redeem')}}"
                                data-datasource="{{url('admin/redeem/data-source-acc')}}"
                                class="display nowrap table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="80px">NO</th>
                                        <th>MEMBER</th>
                                        <th>REWARD</th>
                                        <th>POINT</th>
                                        <th>PENGAJUAN</th>
                                        <th>SETUJUI</th>
                                        <th style="width:100px">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" role="tabpanel" id="tab-tolak">
                        <div class="table-responsive table-responsive-md m-t-40">
                            <table id="jd-table-tolak"
                                width="100%"
                                data-urlaction="{{url('admin/redeem')}}"
                                data-datasource="{{url('admin/redeem/data-source-tolak')}}"
                                class="display nowrap table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="80px">NO</th>
                                        <th>MEMBER</th>
                                        <th>REWARD</th>
                                        <th>POINT</th>
                                        <th>PENGAJUAN</th>
                                        <th>SETUJUI</th>
                                        <th style="width:100px">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@livewire('admin.redeempoint.form')

@endsection

@push('script')
<script src="{{asset('assets/js/controllers/redeempoint.js')}}"></script>
@endpush