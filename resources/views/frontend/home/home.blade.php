@php use Carbon\Carbon; @endphp
@extends('frontend.widget.template')

@section('content')
    @livewireStyles
    @livewireScripts

    <!--====================  hero slider area ====================-->
    <livewire:frontend.slider></livewire:frontend.slider>
    <!--====================  End of hero slider area  ====================-->

    <!--====================  banner three column area ====================-->
    <livewire:frontend.promo></livewire:frontend.promo>

    <!--====================  End of banner three column area ====================-->
    <!--====================  product slider area ====================-->

    <livewire:frontend.youtube></livewire:frontend.youtube>

    <!--====================  End of product slider area  ====================-->
    <!--====================  banner area full  ====================-->

    {{--    <div class="banner-area section-space">--}}
    {{--        <div class="container">--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-lg-12">--}}
    {{--                    <!--=======  single banner  =======-->--}}

    {{--                    <div class="single-banner">--}}
    {{--                        <div class="single-banner__image">--}}
    {{--                            <a href="{{url('product)}}">--}}
    {{--                                <img src="https://www.bottegaartisan.com/uploadbasket/files/pages/asdsa.jpg"--}}
    {{--                                     class="img-fluid" alt="" style="border-radius: 20px; object-fit: cover">--}}
    {{--                            </a>--}}
    {{--                        </div>--}}

    {{--                        <div class="single-banner__content single-banner__content--overlay">--}}
    {{--                            <p class="banner-small-text">STYLING SAVINGS</p>--}}
    {{--                            <p class="banner-big-text">Designer Furniture</p>--}}
    {{--                            <p class="banner-small-text banner-small-text--end">30% Off Armchairs</p>--}}
    {{--                            <a href="{{url('product)}}"--}}
    {{--                               class="theme-button theme-button--banner theme-button--banner--two">SHOP NOW</a>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}

    {{--                    <!--=======  End of single banner  =======-->--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <!--====================  End of banner area full   ====================-->
    <!--====================  product slider with text area ====================-->
    <livewire:frontend.produk></livewire:frontend.produk>
    <!--====================  End of product slider with text area  ====================-->
    <!--====================  blog slider ====================-->

    <livewire:frontend.blog></livewire:frontend.blog>
    <!--====================  End of blog slider  ====================-->
    <!--====================  featured brand ====================-->


    <!--====================  End of featured brand  ====================-->
    <!--====================  product widget area ====================-->

    <livewire:frontend.catalog></livewire:frontend.catalog>

    <!--====================  End of product widget area  ====================-->
@endsection
