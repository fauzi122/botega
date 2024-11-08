<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box" style="overflow: hidden">
                <a href="{{url("/admin/dashboard")}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset("assets/images/logo-e.png") }}" alt="" height="38">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{ asset("assets/images/logo-e.png") }}" alt="" height="38"> <span
                            class="logo-txt"></span>
                                </span>
                </a>

                <a href="{{url("/admin/dashboard")}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset("assets") }}/images/logo-sm.svg" alt="" height="38">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{ asset("assets") }}/images/logo-sm.svg" alt="" height="38"> <span
                            class="logo-txt"></span>
                                </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>


        </div>
        <div>
            <img style="width: 25%;margin-top: 0px; margin-left:10px; " src="{{asset('assets/images/bna.png')}}"/>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                     aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                       aria-label="Search Result">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            @include('admin.widgets.notification')
            @include('admin.widgets.userinfo')


        </div>
    </div>
</header>
