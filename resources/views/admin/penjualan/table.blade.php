@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Penjualan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Penjualan</a></li>
                    <li class="breadcrumb-item active">Penjualan</li>
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
                                <h4 class="card-title">Penjualan</h4>
                                <p class="card-title-desc">
                                    Tambahkan maupun import data penjualan untuk dilakukan analisis.
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/penjualan')}}"
                        data-datasource="{{url('admin/penjualan/data-source')}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NOMOR SO</th>
                                <th>TANGGAL</th>
                                <th>PELANGGAN</th>
                                <th>STATUS</th>
                                <th>TOTAL</th>
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

@livewire('admin.penjualan.formtarikdata')
@endsection

@push('script')
<script src="{{asset('assets/js/controllers/penjualan.js?v=3.2')}}"></script>
@endpush