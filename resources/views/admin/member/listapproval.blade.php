@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Approval Perubahan Data</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                    <li class="breadcrumb-item active">Approval Perubahan Data</li>
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
                                <h4 class="card-title">Approval Perubahan Data</h4>
                                <p class="card-title-desc">
                                    Layanan ini untuk mengizinkan perubahan permintaan data dari member. Setiap pengajuan
                                    perubahan data member harus diberikan izin dan di validasi terlebih dahulu.
                                </p>
                            </div> -->
            <div class="card-body">
                @livewire('admin.member.listapproval-tabel')
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@livewire('admin.member.listapproval-form')
@endsection


@push('script')
<script src="{{url('assets/js/controllers/approval.js')}}"></script>
@endpush