@extends('admin.template')

@section('konten')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Settings</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">System</a></li>
                    <li class="breadcrumb-item"> Settings</a></li>
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
                    <h4 class="card-title">Settings</h4>
                    <p class="card-title-desc">
                       Pengaturan Sistem
                    </p>
                </div> -->
            <div class="card-body">

                @livewire('admin.settings.form')

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


@endsection
@push('script')
<script src="{{asset('assets/js/controllers/sliders.js')}}"></script>
@endpush