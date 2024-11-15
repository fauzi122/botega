
<div class="product-slider-area section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="section-title-area text-center">
                    <h2 class="section-title">Video </h2>
                    <p>Dari eksplorasi dalam dunia desain mebel hingga perjalanan melalui
                        proses kreatif pembuatan kursi eksekutif yang menyatu dengan ergonomi, mari kita temukan
                        lebih dekat beberapa produk unggulan kami melalui video ini.</p>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!--=======  product slider wrapper  =======-->

                <div class="product-slider-wrapper theme-slick-slider" data-slick-setting='{
                        "slidesToShow": 2,
                        "slidesToScroll": 2,
                        "arrows": true,
                        "dots": true,
                        "autoplay": false,
                        "speed": 500,
                        "prevArrow": {"buttonClass": "slick-prev", "iconClass": "fa fa-angle-left" },
                        "nextArrow": {"buttonClass": "slick-next", "iconClass": "fa fa-angle-right" }
                    }' data-slick-responsive='[
                        {"breakpoint":1501, "settings": {"slidesToShow": 2, "slidesToScroll":2, "arrows": false} },
                        {"breakpoint":1199, "settings": {"slidesToShow": 2, "slidesToScroll": 2, "arrows": false} },
                        {"breakpoint":991, "settings": {"slidesToShow": 2,"slidesToScroll": 2, "arrows": true, "dots": false} },
                        {"breakpoint":767, "settings": {"slidesToShow": 2,"slidesToScroll": 2,  "arrows": true, "dots": false} },
                        {"breakpoint":575, "settings": {"slidesToShow": 2, "slidesToScroll": 2,"arrows": false, "dots": true} },
                        {"breakpoint":479, "settings": {"slidesToShow": 1,"slidesToScroll": 1, "arrows": true, "dots": false} }
                    ]'>

                    @foreach($youtube as $you)

                        <div class="col">
                            <div class="">
                                <div class="">
                                    <div class="product-badge-wrapper">

                                    </div>
                                    <div class="video-container" style="border-radius: 20px">
                                        <iframe width="390" height="220" src="{{$you->link_youtube}}" title="{{$you->title}}"
                                                frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                allowfullscreen></iframe>
                                    </div>
                                </div>
                                <div class="single-grid-product__content">

                                </div>
                            </div>


                        </div>
                    @endforeach


                    <!--=======  End of product slider wrapper  =======-->
                </div>
            </div>
        </div>
    </div>
</div>
