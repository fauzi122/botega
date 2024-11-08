@extends('admin.template')

@section('konten')
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Artikel</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Artikel</a></li>
                                    <li class="breadcrumb-item active">Artikel</li>
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
                                <h4 class="card-title">Artikel</h4>
                                <p class="card-title-desc">
                                    Artikel ini untuk membuat konten berita, maupun artikel yang ada di website.
                                </p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-responsive-md m-t-40">
                                    <table id="jd-table"
                                           width="100%"
                                           data-urlaction="{{url('admin/artikel')}}"
                                           data-datasource = "{{url('admin/artikel/data-source')}}"
                                           class="display nowrap table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>TANGGAL DIBUAT</th>
                                            <th>JUDUL</th>
                                            <th>PRODUK</th>
                                            <th>PENULIS</th>
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

                @livewire('admin.artikel.form')

@endsection

@push('script')
    <link rel="stylesheet" href="{{asset('assets01/node_modules/summernote/dist/summernote.css')}}">
    <link rel="stylesheet" href="{{asset('assets01/node_modules/summernote/dist/summernote-bs4.css')}}">
    <script src="{{asset('assets01/node_modules/summernote/dist/summernote.min.js')}}"></script>
    <script src="{{asset('assets01/node_modules/summernote/dist/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('assets/js/controllers/artikel.js')}}"></script>
@endpush
