@extends('admin.template')

@section('konten')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Release Note</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                        <li class="breadcrumb-item active">Release Note</li>
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
                    <div class="table-responsive table-responsive-md">
                        <table id="jd-table" width="100%" data-urlaction="{{ url('admin/release') }}"
                            data-datasource="{{ url('admin/release/data-source') }}"
                            class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>KODE</th>
                                    <th>JUDUL</th>
                                    <th>DESKRIPSI</th>
                                    <th>TIPE</th>
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

    @livewire('admin.release.form')
@endsection

@push('script')
    <script src="{{ asset('assets/js/controllers/release.js') }}"></script>
@endpush
