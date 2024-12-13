@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Form Penjualan</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Penjualan</a></li>
                    <li class="breadcrumb-item "><a href="{{url('admin/penjualan')}}">Penjualan</a></li>
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
                <h4 class="card-title">Form Penjualan</h4>
                <p class="card-title-desc">
                    Tambahkan maupun import data penjualan untuk dilakukan analisis.
                </p>
            </div> -->
            <div class="card-body">

                @livewire('admin.penjualan.form', ['id'=>($transaction?->id ?? 0)])

                <div class="table-responsive table-responsive-md m-t-40">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/penjualan/detail')}}"
                        data-datasource="{{url('admin/penjualan/data-source-detail/?id='.($transaction?->id ?? '') )}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KODE</th>
                                <th>PRODUK</th>
                                <th>HARGA</th>
                                <th>QTY</th>
                                <th>TOTAL HARGA</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endsection

@push('script')
<script src="{{asset('assets/js/controllers/detailpenjualan.js')}}"></script>
@endpush