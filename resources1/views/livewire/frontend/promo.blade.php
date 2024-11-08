<div>
@if($promo->count() == 0)
<div>

</div>
@else
    <div class="banner-area section-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="section-title-area text-center">
                        <h2 class="section-title">Promo Produk</h2>
                        <p>Dari koleksi kursi santai yang ramah hingga kursi eksekutif yang
                            menyatukan kerajinan dengan ergonomi, kami ingin menunjukkan beberapa produk unggulan
                            kami
                            di sini.</p>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!--=======  banner three column wrapper  =======-->

                    <div class="shop-product-wrap shop-product-wrap--fullwidth row grid">
                        @foreach($promo as $pro)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mx-auto">
                                <div class="single-grid-product">
                                    <div class="single-grid-product__image">
                                        <div class="product-badge-wrapper">
                                            <span class="onsale">{{$pro->kategori}}</span>
                                            <span class="hot"
                                                  style="background-color: #0d4982">Rp. {{ number_format($pro->price, 0, ',', '.') }}</span>
                                        </div>
                                        <img src="{{ url('produk-img/imageprimary/'.$pro->product_id.'.png') }}"
                                             class="img-fluid"
                                             alt=""
                                             style="border-radius: 20px; object-fit: cover; width: 100%; height: 400px;"
                                             onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'"
                                        >

                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>

                    <!--=======  End of banner three column wrapper  =======-->
                </div>
            </div>
        </div>
    </div>
@endif
</div>
