@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Approval Permintaan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Approval Permintaan Perubahan Data</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <!-- <div class="card-header">
                                <h4 class="card-title">Approval Permintaan Perubahan Data</h4>
                                <p class="card-title-desc">
                                    Approval Permintaan Perubahan Data untuk menyetujui permintaan perubahan data.
                                </p> -->
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs-custom card-header-tabs border-top mt-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link px-3 active" data-bs-toggle="tab" href="#tab-submited" role="tab" aria-selected="true">Baru</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-approved" role="tab" aria-selected="false" tabindex="-1">Disetujui</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-rejected" role="tab" aria-selected="false" tabindex="-1">Ditolak</a>
                </li>

            </ul>
            <div class="tab-content" style="padding-top: 40px">
                <div class="tab-pane active" role="tabpanel" id="tab-submited">

                    <div class="table-responsive table-responsive-md m-t-40">
                        <table id="jd-table-submit"
                            width="100%"
                            data-urlaction="{{url('admin/approval')}}"
                            data-datasource="{{url('admin/approval/data-source-submit')}}"
                            class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>STATUS</th>
                                    <th>TANGGAL</th>
                                    <th>MEMBER</th>
                                    <th>ALASAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane" role="tabpanel" id="tab-approved">
                    <div class="table-responsive table-responsive-md m-t-40">
                        <table id="jd-table-approved"
                            width="100%"
                            data-urlaction="{{url('admin/approval')}}"
                            data-datasource="{{url('admin/approval/data-source-approved')}}"
                            class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>STATUS</th>
                                    <th>TANGGAL</th>
                                    <th>MEMBER</th>
                                    <th>ALASAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane" role="tabpanel" id="tab-rejected">
                    <div class="table-responsive table-responsive-md m-t-40">
                        <table id="jd-table-reject"
                            width="100%"
                            data-urlaction="{{url('admin/approval')}}"
                            data-datasource="{{url('admin/approval/data-source-reject')}}"
                            class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>STATUS</th>
                                    <th>TANGGAL</th>
                                    <th>MEMBER</th>
                                    <th>ALASAN</th>
                                    <th>AKSI</th>
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

@livewire('admin.approval.form')

@endsection

@push('script')
<script src="{{asset('assets/js/controllers/approval.js')}}"></script>
@endpush