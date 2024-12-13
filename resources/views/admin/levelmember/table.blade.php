@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Level Member</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Level Member</li>
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
                                <h4 class="card-title">Level Member</h4>
                                <p class="card-title-desc">Level member untuk menentukan nomenklatur tingkatan member.
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/level')}}"
                        data-datasource="{{url('admin/level/data-source')}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA LEVEL</th>
                                <th>LEVEL</th>
                                <th>KATEGORI</th>
                                <th>DESKRISPI</th>
                                <th>LIMIT</th>
                                <th>AKTIF</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@livewire('admin.levelmember.form')

@endsection

@push('script')
<script src="{{asset('assets/js/controllers/levelmember.js')}}"></script>
@endpush