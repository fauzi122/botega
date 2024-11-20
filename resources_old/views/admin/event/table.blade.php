@extends('admin.template')

@section('konten')
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Event</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Artikel</a></li>
                                    <li class="breadcrumb-item active">Event</li>
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
                                <h4 class="card-title">Event</h4>
                                <p class="card-title-desc">
                                    Event ini untuk mengelola event apa saja yang akan maupun telah dilaksanakan.
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-responsive-md m-t-40">
                                    <table id="jd-table"

                                           data-urlaction="{{url('admin/event')}}"
                                           data-datasource = "{{url('admin/event/data-source')}}"
                                           class="display nowrap table table-hover table-striped table-bordered"  style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>EVENT</th>
                                            <th>JADWAL</th>
{{--                                            <th  style="width: 100px">KETERANGAN</th>--}}
                                            <th>MEMBER</th>
                                            <th>AKTIF</th>
                                            <th style="width: 100px">AKSI</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div wire:ignore.self id="modalform" class="modal fade" tabindex="-1" role="dialog">
                    <!-- Konten Modal -->
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Event</h5>
                                <button type="button" class="close btn btn-danger" data-bs-dismiss="modal" aria-label="Close" >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul class="nav nav-tabs-custom card-header-tabs border-top " id="pills-tab" role="tablist">

                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3 active" data-bs-toggle="tab" href="#tab" role="tab" aria-selected="true" tabindex="-1">event</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-detail" role="tab" aria-selected="false" tabindex="-1">Detail</a>
                                    </li>

                                </ul>

                                <div class="tab-content" style="padding-top: 10px">
                                    <div id="tab" class="tab-pane active" role="tabpanel">
                                        @livewire('admin.event.form')
                                    </div>
                                    <div id="tab-detail" class="tab-pane" role="tabpanel">
                                        @livewire('admin.event.detailgaleries')
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

@endsection
@push('script')
    <script src="{{asset('assets/js/controllers/event.js')}}"></script>
    <script>

    </script>
@endpush
