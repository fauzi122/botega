@extends('admin.template')

@section('konten')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Release Notes</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                        <li class="breadcrumb-item active">Release Notes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- TAB HEADER -->
                    <ul class="nav nav-tabs-custom card-header-tabs" id="pills-tab" role="tablist">
                        <!-- Tab: BUG -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3 active" data-bs-toggle="tab" href="#tab" role="tab"
                                aria-selected="true">
                                Bug
                                <span class="badge bg-info" id="badge-info-1">0</span>
                            </a>
                        </li>
                        <!-- Tab: iMPROVEMENT -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-improve" role="tab"
                                aria-selected="false">
                                Improvement
                                <span class="badge bg-info" id="badge-info-2">0</span>
                            </a>
                        </li>
                        <!-- Tab: Solved -->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link px-3" data-bs-toggle="tab" href="#tab-solved" role="tab"
                                aria-selected="false">
                                Solved
                                <span class="badge bg-info" id="badge-info-3">0</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" style="padding-top: 50px">
                        <!-- TAB: Solved -->
                        <div class="tab-pane active" role="tabpanel" id="tab">
                            <div class="table-responsive">
                                <table id="jd-table" width="100%" data-urlaction="{{ url('admin/release') }}"
                                    data-datasource="{{ url('admin/release/data-source-bug') }}"
                                    class="display nowrap table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="80px">NO</th>
                                            <th>KODE</th>
                                            <th>JUDUL</th>
                                            <th>DESKRIPSI</th>
                                            <th style="width:100px">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB: Improvement -->
                        <div class="tab-pane" role="tabpanel" id="tab-improve">
                            <div class="table-responsive">
                                <table id="jd-table-improve" width="100%" data-urlaction="{{ url('admin/release') }}"
                                    data-datasource="{{ url('admin/release/data-source-improvement') }}"
                                    class="display nowrap table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="80px">NO</th>
                                            <th>KODE</th>
                                            <th>JUDUL</th>
                                            <th>DESKRIPSI</th>
                                            <th style="width:100px">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB: Solved -->
                        <div class="tab-pane" role="tabpanel" id="tab-solved">
                            <div class="table-responsive">
                                <table id="jd-table-solved" width="100%" data-urlaction="{{ url('admin/release') }}"
                                    data-datasource="{{ url('admin/release/data-source') }}"
                                    class="display nowrap table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="80px">NO</th>
                                            <th>KODE</th>
                                            <th>JUDUL</th>
                                            <th>DESKRIPSI</th>
                                            <th style="width:100px">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- end tab-content -->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->

    @livewire('admin.release.form')
@endsection

@push('script')
    <script src="{{ asset('assets/js/controllers/release.js') }}"></script>
@endpush
