<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bottega & Artisan - {{ $title }}</title>

    <meta name="description" content="Welcome to Bottega Artisan." />
    <meta name="keywords"
        content="Bottega Artisan,Bottega,Artisan,Italica,Exotica,Duraslab,Tiles,Interior,Rohl,Moen,Pullcast,Sun Valley Bronze,Lasvit,Sans Souci,Assa Abloy,Gaggenau,Bosch" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('') }}assets_frontend/img/logoicon2.png">

    <!-- Vendor CSS -->
    <link href="{{ asset('') }}assets_frontend/css/vendors.css" rel="stylesheet">
    <!-- Main CSS -->
    <link href="{{ asset('') }}assets_frontend/css/style.css" rel="stylesheet">


    <!-- Revolution Slider CSS -->
    <link href="{{ asset('') }}assets_frontend/revolution/css/settings.css" rel="stylesheet">
    <link href="{{ asset('') }}assets_frontend/revolution/css/navigation.css" rel="stylesheet">
    <link href="{{ asset('') }}assets_frontend/revolution/custom-setting.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    <!-- Tambahkan di bagian head atau sebelum penutup tag body -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/odometer/0.4.8/themes/odometer-theme-default.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400&family=Poppins:wght@200;300;400;700;800&family=Quicksand:wght@300;400;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/css/dropify.min.css"
        integrity="sha256-AWdeVMUYtwLH09F6ZHxNgvJI37p+te8hJuSMo44NVm0=" crossorigin="anonymous">

    <style>
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-10px);
            }
        }

        .bounce {
            animation: bounce 2s infinite;
        }
    </style>
</head>

<body>

    <div
        class="header-area header-area--default header-area--default--{{ isset($home) ? 'transparent' : 'white' }} header-sticky">

        <!--=======  header navigation wrapper  =======-->

        <div class="header-navigation-area header-navigation-area--extra-space d-none d-lg-block">
            <div class="container wide">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-info-wrapper header-info-wrapper--alt-style">

                            <div class="header-logo">
                                <a href="{{ url('home') }}">
                                    @if (isset($home))
                                        <img src="{{ asset('') }}assets_frontend/img/bottega-brown.png"
                                            class="img-fluid" alt="" style="width: 70%; height: 60%;">
                                    @endif
                                    <img src="{{ asset('') }}assets_frontend/img/bottega-brown.png"
                                        class="img-fluid" alt="" style="width: 70%; height: 60%;">
                                </a>
                            </div>

                            <div class="header-navigation-wrapper">
                                <nav>
                                    <ul>
                                        <li class="has {{ isset($home) ? 'active' : '' }}">
                                            <a href="{{ url('home') }}">HOME</a>

                                        </li>
                                        @if (session('user'))
                                            <li class="has {{ isset($reward) ? 'active' : '' }}">
                                                <a href="{{ url('reward') }}">REWARD</a>
                                            </li>
                                        @endif

                                        <li class="has-children {{ isset($news) ? 'active' : '' }}">
                                            <a href="#">NEWS</a>
                                            <ul class="submenu submenu--column-1">
                                                <li>
                                                    <a href="{{ url('informasi') }}">Informasi Terkini</a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('event') }}">Event</a>
                                                </li>

                                            </ul>
                                        </li>


                                        <li class="has {{ isset($product) ? 'active' : '' }}"><a
                                                href="{{ url('product') }}">PRODUCT</a>

                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            @if (session('user'))
                                <div class="header-icon-area">

                                    <div class="account-dropdown d-flex align-items-center">
                                        <a href="" style="color: black">Selamat Datang,
                                            {{ session('user')->first_name }} <i class="pe-7s-angle-down"></i></a>

                                        <ul class="account-dropdown__list">
                                            <li><a href="{{ url('profile') }}">My Profile</a></li>
                                            {{--                                        <li><a href="{{url('barcode')}}">Barcode QR Member</a></li> --}}
                                            <li><a href="{{ url('ubahpassword') }}">Ubah Password</a></li>
                                            <li><a href="javascript:void(0)" id="labelLogout">Logout</a></li>
                                        </ul>
                                    </div>

                                    <div class="header-icon d-flex align-items-center">
                                        <ul class="header-icon__list">
                                            <li><a href="javascript:void(0)" id="search-icon"><i
                                                        class="fa fa-search"></i></a></li>
                                            <li>
                                                <a href="{{ url('notifications') }}"><i class="fa fa-bell-o"></i>
                                                    @if ($unread > 0)
                                                        <span class="item-count bounce">{{ $unread }}</span>
                                                    @endif

                                                </a>
                                                @if ($unread > 0)
                                                    <div class="container">
                                                        <div class="minicart-wrapper">
                                                            <p class="minicart-wrapper__title">Notification</p>
                                                            <div class="minicart-wrapper__items ps-scroll"
                                                                id="notification-items">
                                                                <!-- Di sini akan ditampilkan notifikasi -->
                                                            </div>
                                                            <div class="minicart-wrapper__buttons mb-0">
                                                                <a href="{{ url('notifications') }}"
                                                                    class="theme-button theme-button--minicart-button mb-0">VIEW
                                                                    NOTIFICATIONS</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif


                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <div class="header-icon-area">
                                    <div class="header-icon d-flex align-items-center">
                                        <a href="{{ url('login') }}"
                                            class="theme-button theme-button--small theme-button--rounded">
                                            <i class="pe-7s-user"></i> Login Member
                                        </a>

                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--=======  End of header navigation wrapper  =======-->

        <!--=======  mobile navigation area  =======-->

        <div class="header-mobile-navigation d-block d-lg-none">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-6 col-md-6">
                        <div class="header-logo">
                            <a href="{{ url('home') }}">
                                <img src="{{ asset('') }}assets_frontend/img/bottega-brown.png" class="img-fluid"
                                    alt="" style="width: 70%; height: 60%;">
                            </a>
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="mobile-navigation text-end">

                            <ul class="header-icon__list header-icon__list">
                                <li>
                                    <a href="{{ url('notifications') }}"><i class="fa fa-bell-o"></i>
                                        @if ($unread > 0)
                                            <span class="item-count bounce">{{ $unread }}</span>
                                        @endif

                                    </a>
                                </li>
                                <li><a href="javascript:void(0)" class="mobile-menu-icon" id="mobile-menu-trigger"><i
                                            class="fa fa-bars"></i></a></li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--=======  End of mobile navigation area  =======-->
    </div>


    <!--====================  header area ====================-->


    <!--====================  End of header area  ====================-->
    @yield('content')
    <!--====================  footer ====================-->

    <div class="footer-area">


        <div class="footer-copyright-area">
            <div class="container wide">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-text text-center">
                            copyright &copy; 2024 <a href="#">Bottega & Artisan</a>. All Rights Reserved
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====================  End of footer  ====================-->
    <!--====================  offcanvas items ====================-->

    <!--=======  offcanvas mobile menu  =======-->

    <div class="offcanvas-mobile-menu" id="offcanvas-mobile-menu">
        <a href="javascript:void(0)" class="offcanvas-menu-close" id="offcanvas-menu-close-trigger">
            <i class="pe-7s-close"></i>
        </a>

        <div class="offcanvas-wrapper">

            <div class="offcanvas-inner-content">
                <div class="offcanvas-mobile-search-area">
                    <form action="{{ url('product') }}" method="get">
                        <input type="search" name="cari" placeholder="Search...">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <nav class="offcanvas-naviagtion">
                    <ul>
                        <li class="has {{ isset($home) ? 'active' : '' }}">
                            <a href="{{ url('home') }}">HOME</a>

                        </li>
                        @if (session('user'))
                            <li class="has {{ isset($reward) ? 'active' : '' }}">
                                <a href="{{ url('reward') }}">REWARD</a>
                            </li>
                        @endif

                        <li class="menu-item-has-children {{ isset($news) ? 'active' : '' }}">
                            <a
                                onclick="this.closest('.menu-item-has-children').querySelector('.menu-expand').click(); return false;">NEWS</a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ url('informasi') }}">Informasi Terkini</a>
                                </li>
                                <li>
                                    <a href="{{ url('event') }}">Event</a>
                                </li>

                            </ul>
                        </li>


                        <li class="has {{ isset($product) ? 'active' : '' }}"><a
                                href="{{ url('product') }}">PRODUCT</a>

                        </li>
                    </ul>
                </nav>

                <div class="offcanvas-widget-area">
                    <div class="off-canvas-contact-widget">
                        <div class="header-contact-info">
                            <ul class="header-contact-info__list">
                                <li style="font-weight: bold">{{ session('user')->first_name }}
                                    {{ session('user')->last_name }} </li>
                                <li><i class="fa fa-user"></i> <a href="{{ url('profile') }}" title="">My
                                        Profile </a></li>
                                <li><i class="fa fa-gear"></i> <a href="{{ url('ubahpassword') }}"
                                        title="">Ubah Password </a>
                                </li>
                                <li><i class="fa fa-arrow-circle-left"></i> <a href="javascript:void(0)"
                                        id="labelLogout">Logout </a>
                                </li>


                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!--=======  End of offcanvas mobile menu  =======-->

    <!--====================  End of offcanvas items  ====================-->
    <!--=======  search overlay  =======-->

    <div class="search-overlay" id="search-overlay">

        <!--=======  close icon  =======-->

        <span class="close-icon search-close-icon">
            <a href="javascript:void(0)" id="search-close-icon">
                <i class="pe-7s-close"></i>
            </a>
        </span>

        <!--=======  End of close icon  =======-->

        <!--=======  search overlay content  =======-->

        <div class="search-overlay-content">
            <div class="input-box">
                <form action="{{ url('product') }}">
                    <input type="search" name="cari" placeholder="Search Products...">
                </form>
            </div>
            <div class="search-hint">
                <span># Hit enter to search or ESC to close</span>
            </div>
        </div>

        <!--=======  End of search overlay content  =======-->
    </div>

    <!--=======  End of search overlay  =======-->
    <!--=============================================
=            quick view         =
=============================================-->

    <div id="qv-1" class="cd-quick-view">
        <div class="cd-slider-wrapper">
            <div class="product-badge-wrapper">
                <span class="onsale">-17%</span>
                <span class="hot">Hot</span>
            </div>
            <ul class="cd-slider">
                <li class="selected"><img
                        src="{{ asset('') }}assets_frontend/img/products/product-9-1-600x800.jpg"
                        alt="Product 2"></li>
                <li><img src="{{ asset('') }}assets_frontend/img/products/product-9-2-600x800.jpg"
                        alt="Product 1"></li>
            </ul> <!-- cd-slider -->

            <ul class="cd-slider-pagination">
                <li class="active"><a href="#0">1</a></li>
                <li><a href="#1">2</a></li>
            </ul> <!-- cd-slider-pagination -->

            <ul class="cd-slider-navigation">
                <li><a class="cd-prev" href="#0"><i class="fa fa-angle-left"></i></a></li>
                <li><a class="cd-next" href="#0"><i class="fa fa-angle-right"></i></a></li>
            </ul> <!-- cd-slider-navigation -->
        </div> <!-- cd-slider-wrapper -->

        <div class="quickview-item-info cd-item-info ps-scroll">

            <h2 class="item-title">Atelier Ottoman Takumi Series</h2>
            <p class="price">
                <span class="main-price discounted">$360.00</span>
                <span class="discounted-price">$300.00</span>
            </p>

            <p class="description">Upholstered velvet ottoman with antique stud detailing. Invite restrained glamour
                and
                on-trend colour into your design scheme with the Eichholtz Ponti Ottoman. Inspired by the neo-classical
                influences of Italian icon, Gio Ponti, this contemporary ottoman collection is presented in a choice of
                velvet and linen colourways.</p>


            <div class="pro-qty d-inline-block">
                <input type="text" value="1">
            </div>

            <div class="add-to-cart-btn d-inline-block">
                <button class="theme-button theme-button--alt">ADD TO CART</button>
            </div>

            <div class="quick-view-other-info">
                <div class="other-info-links">
                    <a href="javascript:void(0)"><i class="fa fa-heart-o"></i> ADD TO WISHLIST</a>
                    <a href="javascript:void(0)"><i class="fa fa-exchange"></i> COMPARE</a>
                </div>
                <table>
                    <tr class="single-info">
                        <td class="quickview-title">SKU:</td>
                        <td class="quickview-value">12345</td>
                    </tr>
                    <tr class="single-info">
                        <td class="quickview-title">Categories:</td>
                        <td class="quickview-value">
                            <a href="#">Decor</a>,
                            <a href="#">Living Room</a>,
                            <a href="#">Furniture</a>
                        </td>
                    </tr>
                    <tr class="single-info">
                        <td class="quickview-title">Tags:</td>
                        <td class="quickview-value">
                            <a href="#">Decor</a>,
                            <a href="#">Light</a>
                        </td>
                    </tr>
                    <tr class="single-info">
                        <td class="quickview-title">Share on:</td>
                        <td class="quickview-value">
                            <ul class="quickview-social-icons">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>


        </div> <!-- cd-item-info -->
        <a href="#0" class="cd-close">Close</a>
    </div>

    <!--=====  End of quick view  ======-->
    <!-- scroll to top  -->
    <button class="scroll-top">
        <i class="fa fa-angle-up"></i>
    </button>
    <!-- end of scroll to top -->
    <!--=============================================
=            JS files        =
=============================================-->

    <!-- Vendor JS -->
    <script src="{{ asset('') }}assets_frontend/js/vendors.js"></script>

    <!-- Active JS -->
    <script src="{{ asset('') }}assets_frontend/js/active.js"></script>

    <!--=====  End of JS files ======-->

    <script src="https://cdn.jsdelivr.net/odometer/0.4.8/odometer.min.js"></script>

    <!-- Revolution Slider JS -->
    <script src="{{ asset('') }}assets_frontend/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script src="{{ asset('') }}assets_frontend/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="{{ asset('') }}assets_frontend/revolution/revolution-active.js"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
    <script type="text/javascript"
        src="{{ asset('') }}assets_frontend/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('') }}assets_frontend/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('') }}assets_frontend/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('') }}assets_frontend/revolution/js/extensions/revolution.extension.layeranimation.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('') }}assets_frontend/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('') }}assets_frontend/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"
        integrity="sha256-SUaao5Q7ifr2twwET0iyXVy0OVnuFJhGVi5E/dqEiLU=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Dropify pada elemen dengan class dropify
            $('.dropify').dropify({
                messages: {
                    'default': 'gambar',
                    'replace': 'Tarik dan Tempel Untuk Ganti',
                    'remove': 'Hapus',
                    'error': 'Ooops, Ada yang salah.'
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif


    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Info!',
                text: '{{ session('info') }}',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ url('datanotif') }}')
                .then(response => response.json())
                .then(data => {
                    const notificationItems = document.getElementById('notification-items');
                    data.data.forEach(notification => {
                        const item = document.createElement('div');
                        item.classList.add('minicart-wrapper__items__single');
                        const time = new Date(notification.created_at);
                        const timeString = monthNames[time.getMonth()];
                        const payload = JSON.parse(notification.payload);
                        // Mendapatkan URL yang dihasilkan oleh Laravel

                        item.innerHTML = `
                    <div class="time">
                        <h2>${time.getDate()}</h2>
                    </div>
                    <div class="time">
                        <h2><span style="font-size: small">${timeString}</span></h2>
                    </div>
                    <div style="margin-left: 10px">
                        <span class="bg-primary text-white">&nbsp;&nbsp;${('0' + time.getHours()).slice(-2)}:${('0' + time.getMinutes()).slice(-2)}&nbsp;&nbsp;</span>

                        <a href="${notification.url}"><span style="font-weight: bold">${notification.actions}</span></a> <br>
                        <span style="font-size: small;line-height: 1;">${payload.description}</span>
                    </div>
                `;
                        notificationItems.appendChild(item);
                    });
                })
                .catch(error => console.error('Error fetching notifications:', error));
        });

        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    </script>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('a#labelLogout').on('click', function() {
                Swal.fire({
                    title: '<strong>Logout</strong>',
                    icon: 'warning',
                    html: 'Apakah anda yakin ingin keluar dari sistem ini ???',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: '<i class="bx bx-check"></i> Logout',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText: '<i class="bx bx-x"></i> Batal',
                    cancelButtonAriaLabel: 'Thumbs down'
                }).then((e) => {
                    if (e.isConfirmed) {
                        $.post(`{{ url('login/logout') }}`, {
                            '_method': 'post',
                            '_token': "{{ csrf_token() }}"
                        }).done(function(e) {

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Anda sudah logut sistem',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((e) => {
                                window.location =
                                    "{{ url('/') }}";

                            })


                        });
                    }
                })
            });
        })
    </script>

    @yield('script')

</body>

</html>
