<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>BAI CIRCLE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ $description ?? env("APP_DESCRIPTION")  }}" name="description" />
    <meta content="{{ env("AUTHOR")  }}" name="author" />
    <!-- App favicon -->
    <link rel="icon" href="{{asset('')}}assets_frontend/img/logoicon2.png">

    <!-- plugin css -->
    <!-- DataTables -->
    <link href="{{asset('')}}assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{asset('')}}assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- preloader css -->
    <link rel="stylesheet" href="{{asset('')}}assets/css/preloader.min.css" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{asset('')}}assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('')}}assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css?v=1.3')}}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}assets01/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">

    <meta name="baseurl" content="{{url("")}}" />
    <meta name="csrf-token" content="{{csrf_token()}}" />

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />--}}

    @livewireStyles
</head>

<body>

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include("admin.panels.topbar")

        @include("admin.panels.leftsidebar")

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield("konten")
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include("admin.panels.footer")
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{asset("assets/js/lib.js")}}"></script>
    <script src="{{asset("assets01/node_modules/select2/dist/js/select2.full.min.js")}}"></script>
    <script src="{{asset('assets/js/pages/form-advanced.init.js')}}"></script>

    <script src="{{asset("assets/js/app.js?v=2.1")}}"></script>
    <script src="{{asset("assets/js/helper.js")}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @livewireScripts
    @stack("script")

</body>

</html>