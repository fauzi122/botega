@extends('admin.template')

@section('konten')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Profile</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pengguna</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm order-2 order-sm-1">
                            <div class="d-flex align-items-start mt-3 mt-sm-0">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xl me-3">
                                        <img src="{{ url('admin/foto/'.request()->user()->id . '.png' )  }}" alt="" class="img-fluid rounded-circle d-block">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <h5 class="font-size-16 mb-1">{{  request()->user()->first_name  }} {{  request()->user()->last_name  }}</h5>

                                        <div class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
                                            <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i>{{request()->user()->email}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto order-1 order-sm-2">

                        </div>
                    </div>

                    <ul class="nav nav-tabs-custom card-header-tabs border-top mt-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link px-3 active" data-bs-toggle="tab" href="#biodata" role="tab">Biodata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3" data-bs-toggle="tab" href="#katasandi" role="tab">Kata Sandi</a>
                        </li>

                    </ul>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="tab-content">
                <div class="tab-pane active" id="biodata" role="tabpanel">
                    @livewire('admin.profileku')
                </div>
                <!-- end tab pane -->

                <div class="tab-pane" id="katasandi" role="tabpanel">
                    @livewire('admin.profile.resetsandi')
                </div>
                <!-- end tab pane -->

            </div>
            <!-- end tab content -->
        </div>
        <!-- end col -->


    </div>
    <!-- end row -->

@endsection

@push('script')
     <script src="{{asset('assets/js/controllers/profileku.js')}}"></script>
@endpush
