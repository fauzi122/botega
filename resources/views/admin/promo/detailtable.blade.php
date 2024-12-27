@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Promo</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item">Promo</li>
                    <li class="breadcrumb-item active">Produk {{ $lvl->name }}</li>
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
                                <h4 class="card-title">Harga {{$lvl->name}}</h4>
                                <p class="card-title-desc">Tentukan harga produk promo berdasarkan level pada layanan ini.
                                </p>
                            </div> -->
            <div class="card-body">
                <div class="table-responsive table-responsive-md m-t-40">
                    <table id="jd-table"
                        width="100%"
                        data-urlaction="{{url('admin/promo')}}"
                        data-datasource="{{url('admin/promo/data-source-detail/?id='.$lvl->id)}}"
                        class="display nowrap table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th style="width: 150px">KODE</th>
                                <th style="width:100px">IMAGE</th>
                                <th>PRODUK</th>
                                <th style="width:100px">HARGA</th>
                                <th style="width:100px">BERLAKU HINGGA</th>
                                <th style="width:40px">AKSI</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@livewire('admin.promo.form',['id'=>$lvl->id])
@endsection

@push('script')
<script src="{{asset('assets/js/controllers/promo.js')}}"></script>
@endpush