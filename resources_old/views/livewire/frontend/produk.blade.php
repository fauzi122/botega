
<div class="product-slider-text-area">
    <!--=======  product slider with text wrapper  =======-->

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-slider-text-wrapper">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="product-slider-text-wrapper__text">
                                <h2 class="title">Produk</h2>
                                <p class="description" style="text-align: justify">Desain yang luar biasa
                                    langsung dari para pembuat. Desain yang abadi dan kerajinan yang baik tak
                                    terpisahkan. Dengan Moen, Anda memiliki banyak opsi di genggaman Anda,
                                    sehingga Anda dapat yakin akan kepuasan yang lebih dari cukup dengan
                                    pembelian ini.</p>
                                <a href="{{url('product')}}" class="slider-text-link">BELANJA SEKARANG! <i
                                        class="fa fa-caret-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <!--=======  product slider wrapper  =======-->

                            <div class="product-slider-wrapper theme-slick-slider" data-slick-setting='{
                                        "slidesToShow": 3,
                                        "slidesToScroll": 3,
                                        "arrows": true,
                                        "dots": true,
                                        "autoplay": false,
                                        "speed": 500,
                                        "prevArrow": {"buttonClass": "slick-prev", "iconClass": "fa fa-angle-left" },
                                        "nextArrow": {"buttonClass": "slick-next", "iconClass": "fa fa-angle-right" }
                                    }' data-slick-responsive='[
                                        {"breakpoint":1501, "settings": {"slidesToShow": 3, "slidesToScroll": 3, "arrows": false} },
                                        {"breakpoint":1199, "settings": {"slidesToShow": 2, "slidesToScroll": 2, "arrows": false} },
                                        {"breakpoint":991, "settings": {"slidesToShow": 2,"slidesToScroll": 2, "arrows": true, "dots": false} },
                                        {"breakpoint":767, "settings": {"slidesToShow": 2,"slidesToScroll": 2,  "arrows": true, "dots": false} },
                                        {"breakpoint":575, "settings": {"slidesToShow": 2, "slidesToScroll": 2,"arrows": true, "dots": false} },
                                        {"breakpoint":479, "settings": {"slidesToShow": 1,"slidesToScroll": 1, "arrows": false, "dots": false} }
                                    ]'>

                                @foreach($produk as $pro)
                                    <div class="col">
                                        <div class="single-grid-product">
                                            <div class="single-grid-product__image"
                                                 style="position: relative; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px">
                                                <a href="" class="image-wrap">
                                                    @if($pro->path_file && Storage::exists($pro->path_file))
                                                        <img
                                                            src="{{ url('produk-img/imageprimary/'.$pro->id.'.png') }}"
                                                            class="img-fluid" alt=""
                                                            style="border-radius: 10px; object-fit: cover; width: 100%; height: 400px;">

                                                    @else
                                                        <img
                                                            src="{{ asset('assets_frontend/img/noimage.png') }}"
                                                            class="img-fluid" alt=""
                                                            style="border-radius: 10px; object-fit: cover; width: 100%; height: 400px;">
                                                    @endif


                                                </a>

                                            </div>
                                            <div class="single-grid-product__content">
                                                <h3 class="title"><a
                                                        href="{{url('product-detail/'.Crypt::encrypt($pro->id) )}}">{{\Illuminate\Support\Str::limit($pro->name,50) }}</a>
                                                </h3>

                                            </div>
                                        </div>

                                        <!--=======  End of single short view product  =======-->
                                    </div>
                                @endforeach

                            </div>
                            <!--=======  End of product slider wrapper  =======-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--=======  End of product slider with text wrapper  =======-->
</div>
