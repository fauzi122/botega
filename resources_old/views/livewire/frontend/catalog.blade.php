<div class="product-widget-area section-space--inner bg--light-grey">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="section-title-area text-center">
                    <h2 class="section-title">E-Catalog</h2>
                    <p>Temukan keindahan dan inovasi dalam koleksi katalog elektronik kami.
                        Dari desain mebel yang memukau hingga pengalaman unik dengan kursi eksekutif yang
                        menggabungkan keahlian dan kenyamanan ergonomis. Jelajahi ragam produk unggulan kami melalui
                        e-catalog ini.</p>


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!--=======  product-widget wrapper  =======-->

                <div class="product-widget-wrapper">
                    <div class="row">


                        @foreach ($katalog as $kat)

                            <div class="col-lg-4 col-md-6" style="margin-top: 20px">
                                <!--=======  single banner  =======-->

                                <div class="single-banner">
                                    <div class="single-banner__image">
                                        <a href="{{url('katalog-produk/berkas/'.$kat->id.'.pdf')}}">
                                            <img src="{{url('katalog-produk/image/'.$kat->id.'.png')}}"
                                                 class="img-fluid" alt="" style="border-radius: 20px;object-fit: cover; height: 500px;width: 100%"  onerror="this.src='{{ asset('assets_frontend/img/noimage.png') }}'">
                                        </a>
                                    </div>

                                    <div class="single-banner__content single-banner__content--overlay">
                                        {{--                                            <p class="banner-small-text">STYLING SAVINGS</p>--}}
                                        <p class="banner-big-text">{{$kat->nama_katalog}}</p>
                                        <p class="banner-small-text banner-small-text--end">Bottega & Artisan</p>
                                        <a href="{{url('katalog-produk/berkas/'.$kat->id.'.pdf')}}" target="_blank"
                                           class="theme-button theme-button--banner theme-button--banner--two">DOWNLOAD</a>
                                    </div>
                                </div>

                                <!--=======  End of single banner  =======-->
                            </div>

                        @endforeach

                    </div>
                </div>

                <!--=======  End of product-widget wrapper  =======-->
            </div>
        </div>
    </div>
</div>
