@php use Carbon\Carbon; @endphp
@extends('frontend.widget.template')
@section('meta')
    <meta charset="UTF-8">
    <meta name="description" content="{{ $list[0]->descriptions }}">

    <!-- Open Graph Meta Tags (for social sharing) -->
    <meta property="og:title" content="{{ $list[0]->judul }}">
    <meta property="og:description" content="{{ $list[0]->descriptions }}">

    <!-- Twitter Meta Tags (for Twitter Cards) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $list[0]->judul }}">
    <meta name="twitter:description" content="{{ $list[0]->descriptions }}">
@endsection
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">

                    <div class="breadcrumb-wrapper">
                        {{--                        <h2 class="page-title">Event Detail</h2> --}}
                        {{--                        <ul class="breadcrumb-list"> --}}
                        {{--                            <li><a href="{{url('home')}}l">Home</a></li> --}}
                        {{--                            <li class="active">Event Detail</li> --}}
                        {{--                        </ul> --}}
                    </div>

                    <!--=======  End of breadcrumb wrapper  =======-->
                </div>
            </div>
        </div>
    </div>



    <div class="page-content-wrapper">
        <!--=======  blog page area  =======-->

        <div class="deal-counter-area section-space">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 img-full">
                        <div class="product-details-slider-area product-details-slider-area--side-move">



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
                                            @foreach ($list as $r)
                                                <div class="single-image">
                                                    <img src="{{ url('image-eventdetail/' . $r->galeri_id . '.png') }}"
                                                        class="img-fluid" alt=""
                                                        style="aspect-ratio: 1/1; object-fit: cover; width: 100%; border: 1px solid #7d5e28;"
                                                        onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                                </div>
                                            @endforeach

                                            {{-- tes --}}
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
                                        @foreach ($list as $r)
                                            {{--                                            tes --}}
                                            <div class="single-image">
                                                <img src="{{ url('image-eventdetail/' . $r->galeri_id . '.png') }}"
                                                    class="img-fluid" alt=""
                                                    style="aspect-ratio: 1/1; object-fit: cover; width: 100%; border: 1px solid #7d5e28;"
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
                            <h2 class="item-title">{{ $list[0]->judul }}</h2>

                            <p class="description">{!! $list[0]->descriptions !!}</p>

                            <div class="price" style="font-family: 'Dosis',sans-serif"><i class="fa fa-calendar"></i>
                                {{ Carbon::parse($list[0]->start)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                s.d {{ Carbon::parse($list[0]->end)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                <div class="price"><i class="fa fa-user"></i> {{ $list[0]->first_name }}
                                    {{ $list[0]->last_name }}
                                </div>
                            </div>



                        </div>

                        <!--=======  End of product details description area  =======-->
                    </div>
                </div>
            </div>
        </div>


        <!--=======  End of blog page area  =======-->

    </div>
    <!--====================  End of page content wrapper  ====================-->
    <!--====================  End of page content wrapper  ====================-->

    <!--=======  single product description tab area  =======-->



    <!--=======  End of single product description tab area  =======-->
@endsection
