<div class="dropdown d-inline-block">
    <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="rounded-circle header-profile-user" src="{{ url('admin/foto/'.session('admin')->id.'.png')  }}"
             alt="">
        <span class="d-none d-xl-inline-block ms-1 fw-medium">{{  session("admin")->first_name  }} {{  session("admin")->last_name  }}</span>
        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <!-- item-->
        <a class="dropdown-item" href="{{url("admin/profile")}}"><i class="mdi mdi mdi-face-man font-size-16 align-middle me-1"></i> Profile</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" onclick="return showModalLogout()"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
    </div>
</div>
