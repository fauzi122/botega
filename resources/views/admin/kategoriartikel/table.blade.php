@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Kategori Artikel</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Artikel</a></li>
                    <li class="breadcrumb-item active">Kategori</li>
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
                                <h4 class="card-title">Kategori Artikel</h4>
                                <p class="card-title-desc">
                                    Kategori Artikel ini digunakan untuk mengelola data kategori untuk digunakan pada Artikel konten website.
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/kategori')}}"
                        data-datasource="{{url('admin/kategori/data-source')}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KATEGORI ARTIKEL</th>
                                <th>PUBLISH</th>
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

@livewire('admin.kategori.form')

@endsection

@push('script')
<script src="{{asset('assets/js/controllers/kategoriartikel.js')}}"></script>
@endpush