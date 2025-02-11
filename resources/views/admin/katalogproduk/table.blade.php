@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Katalog Produk</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Konten Web</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/katalog-produk') }}">Katalog Produk</a></li>
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
                                <h4 class="card-title">Katalog Produk</h4>
                                <p class="card-title-desc">
                                    Kelola Dokumen Katalog Produk di konten website
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <div id="gridview" data-datasource="{{ url('admin/katalog-produk/data-source') }}"></div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@livewire('admin.katalogproduk.form')
<style>
    .align-text-bottom {
        position: absolute;
        bottom: 0;
    }

    img#filefoto {
        object-fit: cover
    }
</style>
@endsection
@push('script')
<script src="{{ asset('assets/js/controllers/katalogproduk.js?v=1') }}"></script>
@endpush