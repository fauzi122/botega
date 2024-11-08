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
                                    <li class="breadcrumb-item active">Fee Member</li>
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
                                <h4 class="card-title">Fee Member</h4>
                                <p class="card-title-desc">
                                   Mengisi data fee member berdasarkan invoice member.
                                </p>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs-custom card-header-tabs border-top mt-4" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3 active" data-bs-toggle="tab" href="#tab-resume" role="tab" aria-selected="true">Baru  <span id="badge-info-0" class="badge bg-danger rounded-pill"></span></a>

                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-pengajuan" role="tab" aria-selected="false" tabindex="-1">Pengajuan   <span id="badge-info-1" class="badge bg-danger rounded-pill"></span></a>

                                    </li>
                                 {{--   <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-proses" role="tab" aria-selected="false" tabindex="-1">Proses</a>
                                    </li>--}}
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-acc" role="tab" aria-selected="false" tabindex="-1">Disetujui   <span id="badge-info-2" class="badge bg-danger rounded-pill"></span></a>

                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-finish" role="tab" aria-selected="false" tabindex="-1">Selesai <span id="badge-info-3" class="badge bg-danger rounded-pill"></span></a>

                                    </li>
                                </ul>
                                <div class="tab-content" style="padding-top: 50px">
                                    <div class="tab-pane active" role="tabpanel" id="tab-resume">
                                        <span class="rata-kanan font-size-20 mb-3" id="sum-info-0"></span>
                                        <div class="table-responsive table-responsive-md m-t-40">
                                            <table id="jd-table"
                                                   width="100%"
                                                   data-urlaction="{{url('admin/fee')}}"
                                                   data-datasource = "{{url('admin/fee/data-source')}}"
                                                   class="display nowrap table table-hover table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkall"> NO</th>
                                                    <th style="width: 60px">AKSI</th>
                                                    <th>NOMOR FEE</th>
                                                    <th>PERIODE</th>
                                                    <th>NAMA PROFESSIONAL</th>
                                                    <th>NPWP</th>
                                                    <th>DPP PENJUALAN</th>
                                                    <th>FEE</th>
                                                    <th>PPH21</th>
                                                    <th>PPH23</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane " role="tabpanel" id="tab-pengajuan">
                                        <span  class="rata-kanan font-size-20 mb-3" id="sum-info-1"></span>
                                        <div class="table-responsive table-responsive-md m-t-40">
                                            <table id="jd-table-pengajuan"
                                                   width="100%"
                                                   data-urlaction="{{url('admin/fee')}}"
                                                   data-datasource = "{{url('admin/fee/data-source-pengajuan')}}"
                                                   class="display nowrap table table-hover table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkall"> NO</th>
                                                    <th style="width: 60px">AKSI</th>
                                                    <th>NOMOR FEE</th>
                                                    <th>PERIODE</th>
                                                    <th>NAMA PROFESSIONAL</th>
                                                    <th>NPWP</th>
                                                    <th>DPP PENJUALAN</th>
                                                    <th>FEE</th>
                                                    <th>PPH21</th>
                                                    <th>PPH23</th>
                                                    <th>TOTAL</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{--
                                    <div class="tab-pane " role="tabpanel" id="tab-proses">
                                        <div class="table-responsive table-responsive-md m-t-40">
                                            <table id="jd-table-proses"
                                                   width="100%"
                                                   data-urlaction="{{url('admin/fee')}}"
                                                   data-datasource = "{{url('admin/fee/data-source-proses')}}"
                                                   class="display nowrap table table-hover table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NOMOR FEE</th>
                                                    <th>PERIODE</th>
                                                    <th>NAMA PROFESSIONAL</th>
                                                    <th>NPWP</th>
                                                    <th>DPP PENJUALAN</th>
                                                    <th>FEE</th>
                                                    <th>PPH21</th>
                                                    <th>PPH23</th>
                                                    <th>TOTAL</th>
                                                    <th>PEMBAYARAN</th>
                                                    <th style="width: 60px">AKSI</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    --}}
                                    <div class="tab-pane " role="tabpanel" id="tab-acc">
                                        <span class="rata-kanan font-size-20 mb-3" id="sum-info-2"></span>
                                        <div class="table-responsive table-responsive-md m-t-40">
                                            <table  id="jd-table-setujui"
                                                   width="100%"
                                                   data-urlaction="{{url('admin/fee')}}"
                                                   data-datasource = "{{url('admin/fee/data-source-setujui')}}"
                                                   class="display nowrap table table-hover table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkall"> NO</th>
                                                    <th style="width: 60px">AKSI</th>
                                                    <th>NOMOR FEE</th>
                                                    <th>PERIODE</th>
                                                    <th>NAMA PROFESSIONAL</th>
                                                    <th>NPWP</th>
                                                    <th>DPP PENJUALAN</th>
                                                    <th>FEE</th>
                                                    <th>PPH21</th>
                                                    <th>PPH23</th>
                                                    <th>TOTAL</th>
                                                    <th>PEMBAYARAN</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane " role="tabpanel" id="tab-finish">
                                        <span class="rata-kanan font-size-20 mb-3" id="sum-info-3"></span>
                                        <div class="table-responsive table-responsive-md m-t-40">
                                            <table id="jd-table-selesai"
                                                   width="100%"
                                                   data-urlaction="{{url('admin/fee')}}"
                                                   data-datasource = "{{url('admin/fee/data-source-selesai')}}"
                                                   class="display nowrap table table-hover table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th> <input type="checkbox" id="checkall"> NO</th>
                                                    <th style="width: 60px">AKSI</th>
                                                    <th>NOMOR FEE</th>
                                                    <th>PERIODE</th>
                                                    <th>NAMA PROFESSIONAL</th>
                                                    <th>NPWP</th>
                                                    <th>DPP PENJUALAN</th>
                                                    <th>FEE</th>
                                                    <th>PPH21</th>
                                                    <th>PPH23</th>
                                                    <th>TOTAL</th>
                                                    <th>NO FAKTUR</th>
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

                @livewire('admin.fee.form')
                @livewire('admin.fee.detailfee')
                @livewire('admin.fee.merger')

                <style>
                    .rata-kanan{
                        display: block;
                        text-align: right;
                    }
                    .table tr th{
                        text-align: center !important;
                    }
                </style>
    <iframe style="display: none" id="frame-download" name="frame-download"></iframe>
@endsection

@push('script')
    <script src="{{asset('assets/js/controllers/fee.js?v=3.64')}}"></script>
@endpush
