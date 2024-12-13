@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Gift</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Reward & Gift</a></li>
                    <li class="breadcrumb-item active">Gift</li>
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
                                <h4 class="card-title">Gift</h4>
                                <p class="card-title-desc">
                                    Tentukan hadiah (Gift) yang disediakan untuk ke para member
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md ">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/gift')}}"
                        data-datasource="{{url('admin/gift/data-source')}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>MEMBER</th>
                                <th>TYPE GIFT</th>
                                <th>PENGIRIMAN</th>
                                <th>PENERIMAAN</th>
                                <th>PENGELOLA</th>
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

@livewire('admin.gift.form')

@endsection
@push('script')
<script src="{{asset('assets/js/controllers/gift.js')}}"></script>
@endpush