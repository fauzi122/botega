
<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i data-feather="bell" class="icon-lg"></i>
        <span class="badge bg-danger rounded-pill" id="txt-countnotif"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
         aria-labelledby="page-header-notifications-dropdown" style="overflow: scroll">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0"> Notifications </h6>
                </div>
                <div class="col-auto">
                    <a href="#!" class="small text-reset text-decoration-underline" id="txt-unread"> </a>
                </div>
            </div>
        </div>
        <div data-simplebar style="max-height: 230px;" id="list-notif">
        </div>
        <div class="p-2 border-top d-grid">
{{--            <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">--}}
{{--                <i class="mdi mdi-arrow-right-circle me-1"></i> <span>View More..</span>--}}
{{--            </a>--}}
        </div>
    </div>
</div>

@push('script')
    <script src="{{asset('assets/js/controllers/notification.js')}}"></script>
@endpush
