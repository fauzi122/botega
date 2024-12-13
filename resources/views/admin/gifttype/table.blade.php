@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gift Type</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Reward & Gift</a></li>
                    <li class="breadcrumb-item active">Gift Type</li>
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
                                <h4 class="card-title">Gift Type</h4>
                                <p class="card-title-desc">
                                    Tentukan jenis hadiah (Gift Type) yang disediakan untuk member
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/gift-type')}}"
                        data-datasource="{{url('admin/gift-type/data-source')}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA HADIAH</th>
                                <th>HARGA</th>
                                <th>DESKRIPSI</th>
                                <th style="width:100px">AKSI</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@livewire('admin.gifttype.form')

@endsection
@push('script')
<script src="{{asset('assets/js/controllers/gifttype.js')}}"></script>
@endpush