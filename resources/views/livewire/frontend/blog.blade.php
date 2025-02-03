<div class="blog-slider-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="section-title-area text-center">
                    <h2 class="section-title">Informasi Terkini</h2>
                    <p>Dapatkan berita terbaru dan informasi terkini tentang produk, tren
                        terkini, serta berbagai penawaran spesial kami di sini.</p>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 p-0">
                <!--=======  blog slider wrapper  =======-->

                <div class="blog-slider-wrapper theme-slick-slider"
                    data-slick-setting='{
                        "slidesToShow": 3,
                        "slidesToScroll": 3,
                        "arrows": true,
                        "dots": true,
                        "autoplay": false,
                        "autoplaySpeed": 5000,
                        "speed": 500,
                        "prevArrow": {"buttonClass": "slick-prev", "iconClass": "fa fa-angle-left" },
                        "nextArrow": {"buttonClass": "slick-next", "iconClass": "fa fa-angle-right" }
                    }'
                    data-slick-responsive='[
                        {"breakpoint":1501, "settings": {"slidesToShow": 3, "arrows": false} },
                        {"breakpoint":1199, "settings": {"slidesToShow": 3, "arrows": false} },
                        {"breakpoint":991, "settings": {"slidesToShow": 2, "arrows": false, "slidesToScroll": 2} },
                        {"breakpoint":767, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} },
                        {"breakpoint":575, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} },
                        {"breakpoint":479, "settings": {"slidesToShow": 1, "arrows": false, "slidesToScroll": 1} }
                    ]'>
                    <!--=======  single blog post  =======-->
                    @foreach ($article as $ar)
                        <div class="col">

                            <div class="single-slider-blog-post">
                                <div class="single-slider-blog-post__image">
                                    <a
                                        href="{{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($ar->id)) }}">
                                        <img src="{{ url('article/image/' . $ar->id . '.png') }}" class="img-fluid"
                                            alt=""
                                            style="border-radius: 0px; width: 100%; object-fit: cover;aspect-ratio: 1/1;"
                                            onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                    </a>
                                </div>
                                <div class="single-slider-blog-post__content">
                                    <h3 class="post-title"><a href="">
                                            {{ $ar->judul }}</a></h3>
                                    <p class="post-meta">By <a href="#"
                                            class="post-author">{{ $ar->first_name . ' ' . $ar->last_name }}</a>
                                        <span class="separator">|</span> <a
                                            href="#">{{ \Carbon\Carbon::parse($ar->published_at)->locale('id_ID')->isoFormat('D MMMM YYYY') }}
                                        </a>
                                    </p>
                                    <p class="post-excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($ar->article), 100) }}</p>

                                    <a href="{{ url('detail-article/' . \Illuminate\Support\Facades\Crypt::encrypt($ar->id)) }}"
                                        class="blog-readmore-link">Read more <i class="fa fa-caret-right"></i></a>
                                </div>
                            </div>

                        </div>
                        <!--=======  End of single blog post  =======-->
                        <!--=======  single blog post  =======-->
                    @endforeach


                </div>

                <!--=======  End of blog slider wrapper  =======-->
            </div>
        </div>
    </div>
</div>
