@extends('frontend.widget.template')

@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!--=======  breadcrumb wrapper  =======-->

                    <div class="sidebar-search" style="margin-bottom: 30px">
                        <form action="{{url('informasi')}}" method="get">
                            <input type="search" name="cari" value="{{$cari}}" placeholder="Search...">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
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

                        <div class="blog-post-wrapper">
                            <div class="row">
                                @foreach($list as $l)
                                    <div class="col-md-4">
                                        <!--=======  single post  =======-->

                                        <div class="single-blog-post">
                                            <div class="single-blog-post__image">
                                                <a href="">
                                                    <img src="{{url('article/image/'.$l->id.'.png')}}" class="img-fluid" alt="" style="border-radius: 20px; width: 100%; height: 300px; object-fit: cover"  onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                                </a>
                                            </div>
                                            <div class="single-blog-post__content">
                                                <h3 class="post-title"><a href="{{url('detail-article/'.\Illuminate\Support\Facades\Crypt::encrypt($l->id))}}">{{$l->judul}}</a></h3>
                                                <p class="post-meta">By <a href="#" class="post-author">{{$l->first_name.' '. $l->last_name}}</a> <span class="separator">|</span> <a href="#">{{ \Carbon\Carbon::parse($l->published_at)->locale('id_ID')->isoFormat('D MMMM YYYY') }}</a> <span class="separator">|</span> {{$l->hit}}x</p>
                                                <p class="post-excerpt" style="text-align: justify">{!!\Illuminate\Support\Str::limit(strip_tags($l->article), 300)!!}</p>
                                                <a href="{{url('detail-article/'.\Illuminate\Support\Facades\Crypt::encrypt($l->id))}}" class="blog-readmore-link">Read more <i class="fa fa-caret-right"></i></a>
                                            </div>
                                        </div>

                                        <!--=======  End of single post  =======-->
                                    </div>

                                @endforeach

                            </div>
                        </div>


                        <!--=======  End of blog post wrapper  =======-->
                        <!--=======  pagination wrapper  =======-->

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

