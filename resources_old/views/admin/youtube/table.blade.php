@extends('admin.template')

@section('konten')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Video Youtube</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Konten Web</a></li>
                        <li class="breadcrumb-item active">Video Youtube</li>
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
                    <h4 class="card-title">Video Youtube</h4>
                    <p class="card-title-desc">
                        Video Youtube ini untuk mengelola Video Youtube yang akan ditampilkan di konten website.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-responsive-md m-t-40">
                        <table id="jd-table"
                               width="100%"
                               data-urlaction="{{url('admin/video')}}"
                               data-datasource = "{{url('admin/video/data-source')}}"
                               class="display nowrap table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>JUDUL</th>
                                <th>LINK</th>
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

    @livewire('admin.video.form')

@endsection
@push('script')
    <script src="{{asset('assets/js/controllers/youtube.js')}}"></script>
@endpush
