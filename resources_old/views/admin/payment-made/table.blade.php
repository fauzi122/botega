@extends('admin.template')

@section('konten')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Payment Made</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Penjualan</a></li>
                        <li class="breadcrumb-item active">Payment Made</li>
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
                    <h4 class="card-title">Payment Made</h4>
                    <p class="card-title-desc">
                        Daftar pembayaran fee yang sudah dilakukan diluar dari sistem ini.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-responsive-md m-t-40">
                        <table id="jd-table"
                               width="100%"
                               data-urlaction="{{url('admin/payment-made')}}"
                               data-datasource = "{{url('admin/payment-made/data-source')}}"
                               class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>NOMOR SO</th>
                                <th>NOMOR SJ</th>
                                <th>NOMOR INV</th>
                                <th>MEMBER</th>
                                <th>DIBAYAR FEE</th>
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

    @livewire('admin.paymentmade.form')
@endsection

@push('script')
    <script src="{{asset('assets/js/controllers/payment-made.js')}}"></script>
@endpush
