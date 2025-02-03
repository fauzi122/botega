@extends('frontend.widget.template')
@section('meta')
    <meta charset="UTF-8">
    <meta name="description" content="{{ $l->judul }}">
    <meta name="author" content="{{ $l->first_name . ' ' . $l->last_name }}">
    <meta name="keywords" content="Bootega, Artisan, Bottega and Artisan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Open Graph Meta Tags (for social sharing) -->
    <meta property="og:title" content="{{ $l->judul }}">
    <meta property="og:description" content="{{ $l->judul }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}">
    <meta property="og:image" content="{{ url('article/image/' . $l->id . '.png') }}">

    <!-- Twitter Meta Tags (for Twitter Cards) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $l->judul }}">
    <meta name="twitter:description" content="{{ $l->judul }}">
    <meta name="twitter:image" content="{{ url('article/image/' . $l->id . '.png') }}">
@endsection
@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <!--=======  breadcrumb wrapper  =======-->


                    <div class="sidebar-search" style="margin-bottom: 30px">
                        <form action="{{ url('informasi') }}" method="get">
                            <input type="search" name="cari" value="" placeholder="Search...">
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
                    {{--                    <div class="col-lg-3 order-2"> --}}
                    {{--                        <!--=======  blog sidebar  =======--> --}}

                    {{--                        <div class="blog-sidebar-wrapper"> --}}

                    {{--                            <!--=======  single sidebar widget  =======--> --}}

                    {{--                            <div class="single-sidebar-widget single-sidebar-widget--extra-space"> --}}
                    {{--                                <h2 class="single-sidebar-widget__title single-sidebar-widget__title--extra-space">Search</h2> --}}

                    {{--                                <div class="sidebar-search"> --}}
                    {{--                                    <form action="{{url('informasi')}}" method="get"> --}}
                    {{--                                        <input type="search" name="cari" placeholder="Search..."> --}}
                    {{--                                        <button type="submit"><i class="fa fa-search"></i></button> --}}
                    {{--                                    </form> --}}
                    {{--                                </div> --}}

                    {{--                            </div> --}}

                    {{--                            <!--=======  End of single sidebar widget  =======--> --}}

                    {{--                            <!--=======  single sidebar widget  =======--> --}}

                    {{--                            <div class="single-sidebar-widget"> --}}
                    {{--                                <h2 class="single-sidebar-widget__title">Popular Posts</h2> --}}
                    {{--                                <ul class="single-sidebar-widget__dropdown single-sidebar-widget__dropdown--extra-height"> --}}
                    {{--                                    @foreach ($populer as $pop) --}}
                    {{--                                        <li><a href="{{url('article-detail/'.\Illuminate\Support\Facades\Crypt::encrypt($pop->id))}}">{{$pop->judul}}</a></li> --}}
                    {{--                                    @endforeach --}}


                    {{--                                </ul> --}}
                    {{--                            </div> --}}

                    {{--                            <!--=======  End of single sidebar widget  =======--> --}}


                    {{--                            <!--=======  single sidebar widget  =======--> --}}

                    {{--                            <div class="single-sidebar-widget"> --}}
                    {{--                                <h2 class="single-sidebar-widget__title">Categories</h2> --}}
                    {{--                                <ul class="single-sidebar-widget__dropdown"> --}}
                    {{--                                    @foreach ($kategori as $kat) --}}
                    {{--                                        <li><a href="{{url('informasi?kat='.$kat->article_category_id)}}">{{$kat->category}}</a></li> --}}
                    {{--                                    @endforeach --}}
                    {{--                                </ul> --}}
                    {{--                            </div> --}}

                    {{--                            <!--=======  End of single sidebar widget  =======--> --}}

                    {{--                        </div> --}}

                    {{--                        <!--=======  End of blog sidebar  =======--> --}}
                    {{--                    </div> --}}
                    <div class="col-lg-12 order-1 p-0">
                        <!--=======  blog single post details wrapper  =======-->

                        <div class="blog-single-post-details-wrapper">

                            <h2 class="post-title">{{ $l->judul }}</h2>
                            <p class="post-meta">By <a href="#"
                                    class="post-author">{{ $l->first_name . ' ' . $l->last_name }}</a> <span
                                    class="separator">|</span> <a
                                    href="#">{{ \Carbon\Carbon::parse($l->published_at)->locale('id_ID')->isoFormat('D MMMM YYYY') }}</a><span
                                    class="separator">|</span> {{ $l->hit }} Dilihat</p>

                            <div class="post-thumbnail">
                                <img src="{{ url('article/image/' . $l->id . '.png') }}" class="img-fluid" alt=""
                                    style="aspect-ratio:1/1; width: 100%; object-fit: cover"
                                    onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                            </div>

                            <div class="post-text-content">

                                {!! $l->article !!}


                            </div>

                            <div class="post-share-section">
                                <span>SHARE :</span>
                                <ul class="post-social-icons">
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}"
                                            target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter.com/intent/tweet?url={{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}"
                                            target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="https://plus.google.com/share?url={{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}"
                                            target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="https://pinterest.com/pin/create/button/?url={{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}"
                                            target="_blank"><i class="fa fa-pinterest"></i></a></li>
                                    <li><a href="https://api.whatsapp.com/send?text={{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($l->id)) }}"
                                            target="_blank"><i class="fa fa-whatsapp"></i></a></li>

                                </ul>
                            </div>

                        </div>

                        <!--=======  End of blog single post details wrapper  =======-->
                        <!--=======  blog related post  =======-->



                        <!--=======  End of blog related post  =======-->
                        <!--=======  blog comments area  =======-->

                        <div class="blog-comments-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="blog-details-section-title">Comments ({{ $comment->count() }})</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <!--=======  blog comments  =======-->

                                    <div class="blog-comments-wrapper">

                                        <!--=======  single blog comment  =======-->

                                        @foreach ($comment as $com)
                                            <div class="single-blog-comment">


                                                <div class="single-blog-comment__content">
                                                    <div class="comment-time"><i class="fa fa-calendar"></i>
                                                        {{ \Carbon\Carbon::parse($l->created_at)->locale('id_ID')->isoFormat('D MMMM YYYY, H:mm') }}
                                                    </div>
                                                    <p class="comment-text">{{ $com->comment }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach


                                        <!--=======  End of single blog comment  =======-->

                                        <!--=======  single blog comment  =======-->



                                        <!--=======  End of single blog comment  =======-->


                                    </div>

                                    <!--=======  End of blog comments  =======-->
                                </div>
                            </div>
                        </div>

                        <!--=======  End of blog comments area  =======-->



                        <!--=======  blog comment form area  =======-->

                        <div class="blog-comment-form-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="blog-details-section-title">Leave a comment</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <!--=======  comment form wrapper  =======-->

                                    <div class="contact-form-wrapper">
                                        <form action="{{ url('post-comment') }}" method="post">
                                            @csrf
                                            <div class="row">
                                                <input type="hidden" name="id"
                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($l->id) }}">
                                                <div class="col-lg-12">
                                                    <textarea cols="30" rows="5" placeholder="Message *" name="comment" required></textarea>
                                                </div>
                                                <div class="col-lg-12">
                                                    <button type="submit" id="submit" class="theme-button"> ADD
                                                        COMMENT</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <!--=======  End of comment form wrapper  =======-->
                                </div>
                            </div>
                        </div>

                        <!--=======  End of blog comment form area  =======-->
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
