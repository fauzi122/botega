@extends('frontend.widget.template')

@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="breadcrumb-wrapper">
{{--                        <h2 class="page-title">Notification</h2>--}}
{{--                        <ul class="breadcrumb-list">--}}
{{--                            <li><a href="{{url('home')}}">Home</a></li>--}}
{{--                            <li class="active">Notification</li>--}}
{{--                        </ul>--}}
                    </div>

                    <!--=======  End of breadcrumb wrapper  =======-->
                </div>
            </div>
        </div>
    </div>

    <!--====================  End of breadcrumb area  ====================-->
    <style>
        a:hover div {
            color: darkorange; /* Ganti dengan warna latar belakang yang diinginkan saat diarahkan */
        }
        .active {
            font-weight: bold;
            color: orange;
        }

    </style>
    <div class="page-content-wrapper">

        <!--====================  faq area ====================-->

        <div class="faq-area section-space">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3" style="margin-bottom: 30px">
                        <div class="single-sidebar-widget single-sidebar-widget--extra-space">
                            <h2 class="single-sidebar-widget__title single-sidebar-widget__title--extra-space">
                                Search</h2>

                            <div class="sidebar-search">
                                <form action="{{url('notifications')}}" method="get">
                                    <input type="search" name="cari" value="{{$cari}}" placeholder="Search...">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>

                        </div>

                        <div class="single-sidebar-widget">
                            <h2 class="single-sidebar-widget__title">Status</h2>

                            <ul class="single-sidebar-widget__dropdown" id="single-sidebar-widget__dropdown">
                                <li><a href="{{url('notifications')}}" ><i class="bx bx-book"></i>
                                        Belum Dibaca</a></li>
                                <li><a href="{{url('notificationss')}}" style="color: orange"><i class="bx bx-book-open"></i> Sudah Dibaca</a>
                                </li>
                            </ul>
                        </div>


                    </div>
                    <div class="col-lg-9">
                        <div class="faq-wrapper">
                            <div class="single-faq">
                                <h2 class="faq-title"><i class="bx bx-book-open"></i> Sudah Dibaca</h2>
                                <div class="accordion row" id="shippingInfo">
                                    @foreach($list as $l)
                                        @php
                                            $decodedPayload = json_decode($l->payload);
                                        @endphp
                                        <a href="{{url('ceknotif/'.Crypt::encrypt($l->id))}}">
                                            <div
                                                style="border: 1px solid #cccce0; border-radius: 8px; padding: 15px; margin: 5px; background-color: white">
                                                <div>
                                                    <h4>{{$l->actions}}</h4>
                                                    <p>{{$decodedPayload->description}}</p>
                                                    <p><i class="fa fa-tags"></i> {{ \Carbon\Carbon::parse($l->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                                                </div>
                                            </div>
                                        </a>

                                    @endforeach

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="pagination-wrapper">
                    <ul>
                        @if($list->onFirstPage())
                            <li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
                        @else
                            <li>
                                <a href="{{ $list->previousPageUrl() }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                            </li>
                        @endif

                        @if($list->currentPage() > 3)
                            <li><a href="{{ $list->url(1) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">1</a></li>
                            <li class="disabled"><span>...</span></li>
                        @endif

                        @foreach(range(max(1, $list->currentPage() - 2), min($list->currentPage() + 2, $list->lastPage())) as $page)
                            @if($page == $list->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li>
                                    <a href="{{ $list->url($page) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        @if($list->currentPage() < $list->lastPage() - 2)
                            <li class="disabled"><span>...</span></li>
                            <li><a href="{{ $list->url($list->lastPage()) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">{{ $list->lastPage() }}</a></li>
                        @endif

                        @if($list->hasMorePages())
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

            </div>
        </div>

        <!--====================  End of faq area  ====================-->


    </div>

    <!--=======  single product description tab area  =======-->



    <!--=======  End of single product description tab area  =======-->
@endsection

@section('script')


@endsection
