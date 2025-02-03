@extends('frontend.widget.template')
@section('meta')
    <meta charset="UTF-8">
    <meta name="description" content="{{ $result[0]->descriptions }}">
    <meta name="keywords" content="Bootega, Artisan, Bottega and Artisan,{{ $result[0]->kode }} ">
    <!-- Anda dapat menambahkan meta tag lainnya sesuai kebutuhan, seperti meta tag untuk kata kunci (keywords) jika tersedia -->

    <!-- Open Graph Meta Tags (for social sharing) -->
    <meta property="og:title" content="{{ $result[0]->name }}">
    <meta property="og:description" content="{{ $result[0]->descriptions }}">

    <meta property="og:type" content="article"> <!-- Anda dapat mengubah "article" sesuai jenis konten yang sesuai -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('produk-img/image/' . $result[0]->idimage . '.png') }}">

    <!-- Twitter Meta Tags (for Twitter Cards) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $result[0]->name }}">
    <meta name="twitter:description" content="{{ $result[0]->descriptions }}">
    <meta name="twitter:image" content="{{ url('produk-img/image/' . $result[0]->idimage . '.png') }}">
@endsection
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">

                    {{-- <div class="breadcrumb-wrapper">
                        <h2 class="page-title">Product</h2>
                        <ul class="breadcrumb-list">
                            <li><a href="{{url('home')}}">Home</a></li>
                            <li class="active">Product Detail</li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>


    <div class="page-content-wrapper">
        <!--=======  single product slider details area  =======-->

        <div class="single-product-slider-details-area">
            <div class="container wide">
                <div class="row">
                    <div class="col-lg-6 img-full">
                        <div class="product-details-slider-area product-details-slider-area--side-move">

                            <div class="product-badge-wrapper">
                                <span class="hot">{{ $result[0]->category }}</span>
                            </div>

                            <div class="row row-5">
                                <div class="col-md-9 order-1 order-md-2">
                                    <div class="big-image-wrapper">
                                        <div class="enlarge-icon">
                                            <a class="btn-zoom-popup" href="javascript:void(0)"
                                                data-tippy="Click to enlarge" data-tippy-placement="left"
                                                data-tippy-inertia="true" data-tippy-animation="shift-away"
                                                data-tippy-delay="50" data-tippy-arrow="true"
                                                data-tippy-theme="sharpborder"><i class="pe-7s-expand1"></i></a>
                                        </div>
                                        <div class="product-details-big-image-slider-wrapper product-details-big-image-slider-wrapper--side-space theme-slick-slider"
                                            data-slick-setting='{
                                            "slidesToShow": 1,
                                            "slidesToScroll": 1,
                                            "arrows": false,
                                            "autoplay": false,
                                            "autoplaySpeed": 5000,
                                            "fade": true,
                                            "speed": 500,
                                            "prevArrow": {"buttonClass": "slick-prev", "iconClass": "fa fa-angle-left" },
                                            "nextArrow": {"buttonClass": "slick-next", "iconClass": "fa fa-angle-right" }
                                        }'
                                            data-slick-responsive='[
                                            {"breakpoint":1501, "settings": {"slidesToShow": 1, "arrows": false} },
                                            {"breakpoint":1199, "settings": {"slidesToShow": 1, "arrows": false} },
                                            {"breakpoint":991, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} },
                                            {"breakpoint":767, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} },
                                            {"breakpoint":575, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} },
                                            {"breakpoint":479, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} }
                                        ]'>
                                            @foreach ($result as $r)
                                                <div class="single-image">
                                                    <img src="{{ url('produk-img/image/' . $r->idimage . '.png') }}"
                                                        class="img-fluid" alt=""
                                                        style="aspect-ratio: 1/1; object-fit: cover; width: 100%; border: 1px solid #7d5e28;"
                                                        onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 order-2 order-md-1">
                                    <div class="product-details-small-image-slider-wrapper product-details-small-image-slider-wrapper--vertical-space theme-slick-slider"
                                        data-slick-setting='{
                                                    "slidesToShow": 3,
                                                    "slidesToScroll": 1,
                                                    "centerMode": false,
                                                    "arrows": true,
                                                    "vertical": true,
                                                    "autoplay": false,
                                                    "autoplaySpeed": 5000,
                                                    "speed": 500,
                                                    "asNavFor": ".product-details-big-image-slider-wrapper",
                                                    "focusOnSelect": true,
                                                    "prevArrow": {"buttonClass": "slick-prev", "iconClass": "fa fa-angle-up" },
                                                    "nextArrow": {"buttonClass": "slick-next", "iconClass": "fa fa-angle-down" }
                                                }'
                                        data-slick-responsive='[
                                                    {"breakpoint":1501, "settings": {"slidesToShow": 3, "arrows": true} },
                                                    {"breakpoint":1199, "settings": {"slidesToShow": 3, "arrows": true} },
                                                    {"breakpoint":991, "settings": {"slidesToShow": 3, "arrows": true, "slidesToScroll": 1} },
                                                    {"breakpoint":767, "settings": {"slidesToShow": 3, "arrows": false, "slidesToScroll": 1, "vertical": false, "centerMode": true} },
                                                    {"breakpoint":575, "settings": {"slidesToShow": 3, "arrows": false, "slidesToScroll": 1, "vertical": false, "centerMode": true} },
                                                    {"breakpoint":479, "settings": {"slidesToShow": 2, "arrows": false, "slidesToScroll": 1, "vertical": false, "centerMode": true} }
                                                ]'>
                                        @foreach ($result as $r)
                                            {{--                                            tes --}}
                                            <div class="single-image">
                                                <img src="{{ url('produk-img/image/' . $r->idimage . '.png') }}"
                                                    class="img-fluid" alt=""
                                                    style="aspect-ratio: 1/1;; object-fit: cover; width: 100%; border: 1px solid #7d5e28;"
                                                    onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!--=======  product details description area  =======-->

                        <div class="product-details-description-wrapper">
                            <h2 class="item-title">{{ $result[0]->name }}</h2>
                            <p class="price">

                            </p>

                            <p class="description">{!! $result[0]->descriptions !!}</p>

                            <div class="add-to-cart-btn d-inline-block">

                                @php
                                    $title = $result[0]->name;
                                    $gambar = url('produk-img/imageprimary/' . $result[0]->id . '.png');
                                    $fullname = session('user')->first_name . ' ' . session('user')->last_name;

                                    //                                                          var_dump($gambar);die();
                                    $harga = number_format($result[0]->price, 0, ',', '.');
                                    $nomorWhatsApp = '+6281120209000';
                                @endphp
                                <a href="https://wa.me/<?php echo $nomorWhatsApp; ?>?text=<?php echo urlencode("Hallo, saya $fullname ingin menanyakan produk:\n\nTitle: $title\n"); ?>" target="_blank"
                                    class="theme-button theme-button--alt" style="border-radius: 20px;">
                                    <i class="fa fa-whatsapp"></i> Customer Care
                                </a>


                            </div>

                            <div class="quick-view-other-info">


                                <table>
                                    <tr class="single-info">
                                        <td class="quickview-title">Kategori: </td>
                                        <td class="quickview-value">{{ $result[0]->category }}</td>
                                    </tr>
                                    <tr class="single-info">
                                        <td class="quickview-title">KODE: </td>
                                        <td class="quickview-value">{{ $result[0]->kode }}</td>
                                    </tr>

                                    <tr class="single-info">
                                        <td class="quickview-title">Share on: </td>
                                        <td class="quickview-value">
                                            <ul class="quickview-social-icons">
                                                <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ url('product-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($result[0]->id)) }}"
                                                        target="_blank"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="https://twitter.com/intent/tweet?url={{ url('product-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($result[0]->id)) }}"
                                                        target="_blank"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="https://plus.google.com/share?url={{ url('product-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($result[0]->id)) }}"
                                                        target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="https://pinterest.com/pin/create/button/?url={{ url('product-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($result[0]->id)) }}"
                                                        target="_blank"><i class="fa fa-pinterest"></i></a></li>
                                                <li><a href="whatsapp://send?text={{ url('product-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($result[0]->id)) }}"
                                                        target="_blank"><i class="fa fa-whatsapp"></i></a></li>

                                            </ul>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <!--=======  End of product details description area  =======-->
                    </div>
                </div>
            </div>
        </div>

        <!--=======  End of single product slider details area  =======-->

        <!--=======  single product description tab area  =======-->

        <div class="single-product-description-tab-area section-space">
            <!--=======  description tab navigation  =======-->

            <div class="description-tab-navigation">
                <ul class="nav nav-tabs justify-content-center" id="nav-tab2" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="description-tab" type="button" data-bs-toggle="tab"
                            data-bs-target="#product-description" role="tab" aria-controls="product-description"
                            aria-selected="true">DESCRIPTION</button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#product-review"
                            role="tab" aria-controls="product-review" aria-selected="false">REVIEWS
                            ({{ $komentar->count() }})</button>
                    </li>
                </ul>
            </div>

            <!--=======  End of description tab navigation  =======-->

            <!--=======  description tab content  =======-->


            <div class="single-product-description-tab-content">

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="product-description" role="tabpanel"
                        aria-labelledby="description-tab">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--=======  description content  =======-->

                                    <div class="description-content">
                                        <p class="long-desc">
                                            {!! $result[0]->descriptions !!}
                                        </p>

                                    </div>

                                    <!--=======  End of description content  =======-->
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="tab-pane fade" id="product-review" role="tabpanel" aria-labelledby="review-tab">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!--=======  review content  =======-->

                                    <div class="review-content-wrapper">
                                        <!--=======  review comments  =======-->

                                        <div class="review-comments">

                                            <h4 class="review-comment-title">{{ $komentar->count() }} REVIEWS FOR
                                                {{ $result[0]->name }}</h4>

                                            <!--=======  single-review comment  =======-->
                                            @foreach ($komentar as $kom)
                                                <div class="single-review-comment">
                                                    <div class="single-review-comment__image">
                                                        <img src="assets/img/review/one.png" class="img-fluid"
                                                            alt="">
                                                    </div>

                                                    <div class="single-review-comment__content">
                                                        <div class="review-time"><i
                                                                class="fa fa-calendar"></i>{{ \Carbon\Carbon::parse($kom->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY, H:mm') }}
                                                        </div>

                                                        <div class="rating">

                                                        </div>

                                                        <p class="review-text">{{ $kom->comment }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <!--=======  End of single-review comment  =======-->

                                            <!--=======  single-review comment  =======-->

                                        </div>

                                        <!--=======  End of review comments  =======-->

                                        <!--=======  review comment form  =======-->

                                        <div class="review-comment-form">
                                            <h4 class="review-comment-title">Add a review</h4>


                                            <form action="{{ url('komentar-produk') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ Crypt::encrypt($result[0]->id) }}" id="">

                                                <div class="form-group mb-3">
                                                    <label for="reviewComment">Your review <span>*</span></label>
                                                    <textarea name="reviewComment" id="reviewComment" class="form-control" cols="10" rows="5" required></textarea>
                                                </div>

                                                <button type="submit" class="theme-button">SUBMIT</button>
                                            </form>
                                        </div>

                                        <!--=======  End of review comment form  =======-->
                                    </div>

                                    <!--=======  End of review content  =======-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!--=======  End of description tab content  =======-->

        </div>

        <!--=======  End of single product description tab area  =======-->

        <!--====================  related product slider area ====================-->



        <!--====================  End of related product slider area  ====================-->

    </div>
@endsection
@section('script')
    <!-- Di dalam Blade View atau template Anda -->
    <script>
        function toggleLike(id, isLiked, likes) {
            console.log(id);
            var isAuthenticated = true; // Ganti ini berdasarkan logika otentikasi Anda

            if (isAuthenticated) {
                // Toggle the like status
                isLiked = !isLiked;


                var likeIcon = document.getElementById("likeIcon" + id);
                var likeCount = document.getElementById("likeCount" + id);



                // Kirim permintaan AJAX menggunakan Fetch API
                fetch('/productlikes', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            postId: id,
                            isLiked: isLiked,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Tangani respons server jika diperlukan
                        console.log(data);

                        $('span#likeCount' + id).html(data.likes);
                        likeIcon.classList.remove('fa-heart', 'fa-heart-o');
                        likeIcon.classList.add(data.status == false ? 'fa-heart' : 'fa-heart-o');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                // Redirect the user to the login page or show a login modal
                alert('Anda harus login untuk memberikan suka.');
                // Anda dapat menambahkan logika untuk mengarahkan atau menampilkan modal login di sini
            }
        }
    </script>
@endsection
