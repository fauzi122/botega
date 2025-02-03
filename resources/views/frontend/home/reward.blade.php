@php use SimpleSoftwareIO\QrCode\Facades\QrCode; @endphp
@php use Carbon\Carbon; @endphp
@extends('frontend.widget.template')

@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            {{--            <div class="row"> --}}
            {{--                <div class="col-lg-6 offset-lg-3"> --}}
            {{--                    <!--=======  breadcrumb wrapper  =======--> --}}

            {{--                    <div class="breadcrumb-wrapper"> --}}
            {{--                        <h2 class="page-title">Reward</h2> --}}
            {{--                        <ul class="breadcrumb-list"> --}}
            {{--                            <li><a href="{{url('home')}}">Home</a></li> --}}
            {{--                            <li class="active">Reward</li> --}}
            {{--                        </ul> --}}
            {{--                    </div> --}}

            {{--                    <!--=======  End of breadcrumb wrapper  =======--> --}}
            {{--                </div> --}}
            {{--            </div> --}}
        </div>
    </div>

    <!--====================  End of breadcrumb area  ====================-->

    <!--====================  page content wrapper ====================-->

    <div class="page-content-wrapper">
        <!--=======  blog element wrapper  =======-->

        <div class="blog-element-wrapper section-space">
            <!--====================  deal counter area ====================-->

            <div class="deal-counter-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!--=======  deal counter wrapper  =======-->

                            <div class="deal-counter-wrapper">
                                <div class="row">
                                    <div class="col-xl-3 offset-xl-1 col-lg-6">
                                        <div class="deal-counter-wrapper__image">

                                            {!! QrCode::size(150)->generate(url(session('user')->id_no)) !!}
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-6">
                                        <div class="deal-counter-wrapper__content">

                                            <h2 class="title">{{ $cek->first_name . ' ' . $cek->last_name }}</h2>
                                            @php
                                                $level = $cek->level_name;
                                                $levelColors = [
                                                    'Bronze' => '#cd7f32',
                                                    'Silver' => '#C0C0C0',
                                                    'Gold' => '#FFD700',
                                                    'Diamond' => '#B9F2FF',
                                                    'Platinum' => '#00FF7F',
                                                ];
                                            @endphp

                                            <div class="col" style="display: flex; align-items: center;">
                                                <a href="#"
                                                    class="theme-button theme-button--alt theme-button--deal-counter"
                                                    style="background-color: {{ isset($levelColors[$level]) ? $levelColors[$level] : '#cd7f32' }};">
                                                    @if ($level !== null && array_key_exists($level, $levelColors))
                                                        {{ $level }}
                                                    @else
                                                        Bronze
                                                    @endif
                                                </a>
                                                <div style="display: flex; align-items: center;">
                                                    <span class="odometer" data-count="{{ $cek->points }}"
                                                        style="margin-right: 10px; margin-left: 10px; font-weight: bold; font-size: 27px; color: goldenrod">00</span>
                                                    <h2 style="margin: 0; color: goldenrod">Points</h2>
                                                </div>


                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--=======  End of deal counter wrapper  =======-->
                        </div>
                    </div>
                </div>
            </div>

            <!--====================  End of deal counter area  ====================-->
        </div>

        <div class="single-product-description-tab-area section-space">
            <!--=======  description tab navigation  =======-->

            <div class="description-tab-navigation">
                <ul class="nav nav-tabs justify-content-center" id="nav-tab2" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" id="additional-info-tab" data-bs-toggle="tab"
                            data-bs-target="#product-additional-info" role="tab" aria-controls="product-additional-info"
                            aria-selected="false">REWARD
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#product-review"
                            role="tab" aria-controls="product-review" aria-selected="false">GIFT
                        </button>
                    </li>
                </ul>
            </div>

            <!--=======  End of description tab navigation  =======-->

            <!--=======  description tab content  =======-->
            <div class="page-content-wrapper">

                <div class="single-product-description-tab-content">

                    <div class="tab-content">


                        <div class="tab-pane fade active" id="product-additional-info" role="tabpanel"
                            aria-labelledby="additional-info-tab">
                            <div class="container">
                                <div class="shop-product-wrap row grid">
                                    @foreach ($reward as $r)
                                        <div class="col-lg-2 col-md-6 col-sm-6 col-custom-sm-6 col-6">

                                            <!--=======  grid view product  =======-->

                                            <div class="single-grid-product">
                                                <div class="single-grid-product__image">
                                                    <div class="product-badge-wrapper">
                                                        <span class="onsale">{{ $r->name }}</span>
                                                    </div>
                                                    <a href="">
                                                        <img src="{{ url('reward-image/' . $r->id . '.png') }}"
                                                            style="aspect-ratio: 1/1; object-fit: cover;width: 100%"
                                                            class="img-fluid" alt=""
                                                            onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">

                                                    </a>

                                                </div>
                                                <div class="single-grid-product__content"
                                                    style="display: flex; justify-content: space-between; align-items: center;">

                                                    <div class="price"><span
                                                            style="color: darkorange; font-weight: bold;">{{ $r->point }}
                                                            POINT</span>
                                                    </div>
                                                    <div>
                                                        @if ($r->status == 1 && $r->user_id == session('user')->id)
                                                            <button class="btn btn-secondary btn-sm"
                                                                style="border-radius: 20px">Sudah Klaim
                                                            </button>
                                                        @else
                                                            <button class="btn btn-primary btn-sm"
                                                                style="border-radius: 20px; "
                                                                onclick="klaimReward({{ $r->id }})">Klaim
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!--=======  End of grid view product  =======-->

                                            <!--=======  list view product  =======-->

                                            <div class="single-list-product">

                                                <div class="single-list-product__image">
                                                    <a href="#" class="favorite-icon" data-tippy="Add to Wishlist"
                                                        data-tippy-inertia="true" data-tippy-animation="shift-away"
                                                        data-tippy-delay="50" data-tippy-arrow="true"
                                                        data-tippy-theme="sharpborder" data-tippy-placement="left">
                                                        <i class="fa fa-heart-o"></i>
                                                        <i class="fa fa-heart"></i>
                                                    </a>

                                                    <div class="product-badge-wrapper">
                                                        <span class="onsale">-17%</span>
                                                        <span class="hot">Hot</span>
                                                    </div>
                                                    <a href="product-details-basic.html" class="image-wrap">
                                                        <img src="{{ asset('') }}assets_frontend/img/products/product-9-1-600x800.jpg"
                                                            class="img-fluid" alt="">
                                                        <img src="{{ asset('') }}assets_frontend/img/products/product-9-2-600x800.jpg"
                                                            class="img-fluid" alt="">
                                                    </a>


                                                </div>

                                                <div class="single-list-product__content">
                                                    <h3 class="title"><a href="product-details-basic.html">Lighting
                                                            Lamp</a></h3>
                                                    <div class="price"><span class="main-price discounted">$145</span>
                                                        <span class="discounted-price">$110</span>
                                                    </div>
                                                    <p class="product-short-desc">Lorem ipsum dolor sit amet,
                                                        consectetur adipisicing elit. Dolorem quod optio quaerat in
                                                        molestiae amet repudiandae repellendus eveniet libero mollitia.
                                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>

                                                    <div class="color">
                                                        <ul>
                                                            <li><a class="active" href="#" data-tippy="Black"
                                                                    data-tippy-inertia="true"
                                                                    data-tippy-animation="shift-away"
                                                                    data-tippy-delay="50" data-tippy-arrow="true"
                                                                    data-tippy-theme="roundborder"><span
                                                                        class="color-picker black"></span></a></li>
                                                            <li><a href="#" data-tippy="Blue"
                                                                    data-tippy-inertia="true"
                                                                    data-tippy-animation="shift-away"
                                                                    data-tippy-delay="50" data-tippy-arrow="true"
                                                                    data-tippy-theme="roundborder"><span
                                                                        class="color-picker blue"></span></a></li>
                                                            <li><a href="#" data-tippy="Brown"
                                                                    data-tippy-inertia="true"
                                                                    data-tippy-animation="shift-away"
                                                                    data-tippy-delay="50" data-tippy-arrow="true"
                                                                    data-tippy-theme="roundborder"><span
                                                                        class="color-picker brown"></span></a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="product-hover-icon-wrapper">
                                                        <span class="single-icon single-icon--quick-view"><a
                                                                class="cd-trigger" href="#qv-1" data-tippy="Quick View"
                                                                data-tippy-inertia="true"
                                                                data-tippy-animation="shift-away" data-tippy-delay="50"
                                                                data-tippy-arrow="true" data-tippy-theme="sharpborder"><i
                                                                    class="fa fa-search"></i></a></span>
                                                        <span class="single-icon single-icon--add-to-cart"><a
                                                                href="#" data-tippy="Add to cart"
                                                                data-tippy-inertia="true"
                                                                data-tippy-animation="shift-away" data-tippy-delay="50"
                                                                data-tippy-arrow="true" data-tippy-theme="sharpborder"><i
                                                                    class="fa fa-shopping-basket"></i> <span>ADD TO
                                                                    CART</span></a></span>
                                                        <span class="single-icon single-icon--compare"><a href="#"
                                                                data-tippy="Compare" data-tippy-inertia="true"
                                                                data-tippy-animation="shift-away" data-tippy-delay="50"
                                                                data-tippy-arrow="true" data-tippy-theme="sharpborder"><i
                                                                    class="fa fa-exchange"></i></a></span>
                                                    </div>

                                                </div>
                                            </div>

                                            <!--=======  End of list view product  =======-->

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="product-review" role="tabpanel" aria-labelledby="review-tab">
                            <div class="container">
                                <div class="row">
                                    @foreach ($gifts as $g)
                                        <div class="col-lg-12 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <h5 class="card-title">{{ $g->name }}</h5>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="card-text">{{ $g->description }}</p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p class="card-text"> <i class="fa fa-truck"></i>
                                                                {{ Carbon::parse($g->sent_at)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                    </div>


                </div>

                <!--=======  End of description tab content  =======-->

            </div>
        </div>

        <!--=======  End of blog element wrapper  =======-->
    </div>

    <!--====================  End of page content wrapper  ====================-->

    <!--=======  single product description tab area  =======-->



    <!--=======  End of single product description tab area  =======-->
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Temukan elemen dengan kelas "odometer"
            var odometerElement = document.querySelector('.odometer');

            // Dapatkan nilai data-count dari elemen
            var targetValue = parseInt(odometerElement.getAttribute('data-count'), 10);

            // Inisialisasi odometer
            var odometer = new Odometer({
                el: odometerElement,
                value: 0, // Nilai awal odometer
                format: 'd' // Format angka (misalnya 'd' untuk bilangan bulat)
            });

            // Mulai animasi odometer dengan nilai dari data-count
            odometer.update(targetValue);
        });
    </script>
    <script>
        function klaimReward(rewardId) {
            console.log(rewardId);
            // Tampilkan SweetAlert konfirmasi
            Swal.fire({
                title: 'Konfirmasi Klaim',
                text: 'Apakah Anda yakin ingin melakukan klaim untuk reward ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Klaim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                // Jika pengguna menekan tombol "Ya"
                if (result.isConfirmed) {
                    // Lakukan aksi klaim di sini (contoh: permintaan AJAX)
                    $.ajax({
                        url: '/klaim-reward/' +
                        rewardId, // Gantilah dengan URL endpoint yang sesuai di server Anda
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }, // Jangan lupa untuk menyertakan _token jika menggunakan Laravel
                        success: function(response) {
                            console.log('Klaim Berhasil:', response);
                            // Tampilkan SweetAlert berhasil
                            Swal.fire({
                                title: 'Klaim Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                didClose: () => {
                                    // Reload halaman setelah alert berhasil ditutup
                                    console.log('Reloading...');
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            console.error('Klaim Gagal:', response);
                            // Tampilkan SweetAlert gagal
                            Swal.fire({
                                title: 'Klaim Gagal!',
                                text: response.responseJSON.message,
                                icon: 'error',
                                timer: 2000,
                                showCancelButton: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                didClose: () => {
                                    // Reload halaman setelah alert gagal ditutup
                                    console.log('Reloading...');
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
