@php use Carbon\Carbon; @endphp
@extends('frontend.widget.template')

@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="breadcrumb-wrapper">
                        {{--                        <h2 class="page-title">Event</h2> --}}
                        {{--                        <ul class="breadcrumb-list"> --}}
                        {{--                            <li><a href="{{url('home')}}">Home</a></li> --}}
                        {{--                            <li class="active">Event</li> --}}
                        {{--                        </ul> --}}
                    </div>

                    <!--=======  End of breadcrumb wrapper  =======-->
                </div>
            </div>
        </div>
    </div>

    <!--====================  End of breadcrumb area  ====================-->

    <!--====================  page content wrapper ====================-->


    <div class="page-content-wrapper">
        <!--=======  blog page area  =======-->

        <div class="blog-page-area">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 order-1">
                        <!--=======  blog post wrapper  =======-->
                        <div class="sidebar-search" style="margin-bottom: 30px">
                            <form action="{{ url('event') }}" method="get">
                                <input type="search" name="cari" value="{{ $cari }}" placeholder="Search...">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="blog-post-wrapper">
                            <div class="row">
                                @foreach ($list as $l)
                                    <div class="col-md-4">
                                        <div class="single-blog-post">
                                            <div class="single-product-widget-wrapper">

                                                <div class="single-widget-product">
                                                    <div class="single-widget-product__image">
                                                        <a
                                                            href="{{ url('event-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}">
                                                            <img src="{{ url('image-event/' . $l->id . '.png') }}"
                                                                class="img-fluid"
                                                                style="width: 100px; height: 100px;object-fit: cover"
                                                                onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                                        </a>
                                                    </div>
                                                    <div class="single-widget-product__content">
                                                        <h3 class="title" style="font-family: 'Quicksand',sans-serif"><a
                                                                href="{{ url('event-detail/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}">{{ $l->judul }}</a>
                                                        </h3>
                                                        <div class="price" style="font-family: 'Dosis',sans-serif"><i
                                                                class="fa fa-calendar"></i>
                                                            {{ Carbon::parse($l->start)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                                            s.d
                                                            {{ Carbon::parse($l->end)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                                            <div class="price">
                                                                {{ \Illuminate\Support\Str::limit($l->descriptions, 50) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--=======  End of single post  =======-->
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <!--=======  End of blog post wrapper  =======-->
                        <!--=======  pagination wrapper  =======-->

                        <div class="pagination-wrapper">
                            <ul>
                                @if ($list->onFirstPage())
                                    <li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
                                @else
                                    <li>
                                        <a
                                            href="{{ $list->previousPageUrl() }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                    </li>
                                @endif

                                @if ($list->currentPage() > 3)
                                    <li><a href="{{ $list->url(1) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">1</a>
                                    </li>
                                    <li class="disabled"><span>...</span></li>
                                @endif

                                @foreach (range(max(1, $list->currentPage() - 2), min($list->currentPage() + 2, $list->lastPage())) as $page)
                                    @if ($page == $list->currentPage())
                                        <li class="active"><span>{{ $page }}</span></li>
                                    @else
                                        <li>
                                            <a href="{{ $list->url($page) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach

                                @if ($list->currentPage() < $list->lastPage() - 2)
                                    <li class="disabled"><span>...</span></li>
                                    <li><a
                                            href="{{ $list->url($list->lastPage()) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">{{ $list->lastPage() }}</a>
                                    </li>
                                @endif

                                @if ($list->hasMorePages())
                                    <li>
                                        <a href="{{ $list->nextPageUrl() }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="disabled"><span><i class="fa fa-angle-right"></i></span></li>
                                @endif
                            </ul>
                        </div>

                        <!--=======  End of pagination wrapper  =======-->
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
