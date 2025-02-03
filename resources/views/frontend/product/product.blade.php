@extends('frontend.widget.template')

@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">

                    <div class="breadcrumb-wrapper">
                        {{--                        <h2 class="page-title">Product</h2> --}}
                        {{--                        <ul class="breadcrumb-list"> --}}
                        {{--                            <li><a href="{{url('home')}}">Home</a></li> --}}
                        {{--                            <li class="active">Product</li> --}}
                        {{--                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <!--=======  shop page area  =======-->

        <div class="shop-page-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 d-none">
                        <!--=======  shop sidebar wrapper  =======-->

                        <div class="shop-sidebar-wrapper">


                            <!--=======  single sidebar widget  =======-->

                            <div class="single-sidebar-widget">
                                <h2 class="single-sidebar-widget__title">Filter By Price</h2>
                                <div class="sidebar-price">
                                    <div id="price-range"></div>
                                    <div class="output-wrapper">
                                        <input type="text" id="price-amount" class="price-amount" readonly>
                                    </div>
                                </div>
                            </div>

                            <!--=======  End of single sidebar widget  =======-->

                            <!--=======  single sidebar widget  =======-->

                            <div class="single-sidebar-widget">
                                <h2 class="single-sidebar-widget__title">Product Categories</h2>

                                <ul class="single-sidebar-widget__dropdown" id="single-sidebar-widget__dropdown">
                                    <li class="has-children"><a href="shop-left-sidebar.html">Bathroom</a>
                                        <ul class="sub-menu">
                                            <li><a href="shop-left-sidebar.html">Bathroom Accessories</a></li>
                                            <li><a href="shop-left-sidebar.html">Bathroom Storage</a></li>
                                            <li><a href="shop-left-sidebar.html">Bathroom Textiles</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href="shop-left-sidebar.html">Bedroom</a>
                                        <ul class="sub-menu">
                                            <li><a href="shop-left-sidebar.html">Bedroom Lighting</a></li>
                                            <li><a href="shop-left-sidebar.html">Bedroom Textiles & Rugs</a></li>
                                            <li><a href="shop-left-sidebar.html">Beds</a></li>
                                            <li><a href="shop-left-sidebar.html">Wardrobes</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href="shop-left-sidebar.html">Dining Room</a>
                                        <ul class="sub-menu">
                                            <li><a href="shop-left-sidebar.html">Dining Chairs</a></li>
                                            <li><a href="shop-left-sidebar.html">Dining sets</a></li>
                                            <li><a href="shop-left-sidebar.html">Dining Tables</a></li>
                                            <li><a href="shop-left-sidebar.html">Stools & Benches</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href="shop-left-sidebar.html">Living Room</a>
                                        <ul class="sub-menu">
                                            <li><a href="shop-left-sidebar.html">Armchairs</a></li>
                                            <li><a href="shop-left-sidebar.html">Cabinets</a></li>
                                            <li><a href="shop-left-sidebar.html">Chairs</a></li>
                                            <li><a href="shop-left-sidebar.html">Decorative Lighting</a></li>
                                            <li><a href="shop-left-sidebar.html">Footstools</a></li>
                                            <li><a href="shop-left-sidebar.html">Living Room Lighting</a></li>
                                            <li><a href="shop-left-sidebar.html">Sofas</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="shop-left-sidebar.html">Outdoor</a></li>
                                    <li><a href="shop-left-sidebar.html">Uncategorized</a></li>
                                </ul>
                            </div>

                            <!--=======  End of single sidebar widget  =======-->

                            <!--=======  single sidebar widget  =======-->

                            <div class="single-sidebar-widget">
                                <h2 class="single-sidebar-widget__title">Filter By Brand</h2>
                                <ul class="single-sidebar-widget__dropdown">
                                    <li><a href="shop-left-sidebar.html">Alexa</a></li>
                                    <li><a href="shop-left-sidebar.html">Benington</a></li>
                                    <li><a href="shop-left-sidebar.html">Candice</a></li>
                                    <li><a href="shop-left-sidebar.html">Juliet Rowley</a></li>
                                    <li><a href="shop-left-sidebar.html">Olivia Shayn</a></li>
                                    <li><a href="shop-left-sidebar.html">Sarah Stencil</a></li>
                                </ul>
                            </div>

                            <!--=======  End of single sidebar widget  =======-->
                        </div>

                        <!--=======  End of shop sidebar wrapper  =======-->
                    </div>

                    <div class="col-lg-12">
                        <!--=======  shop content wrapper  =======-->
                        <div class="sidebar-search" style="margin-bottom: 30px">
                            <form action="{{ url('product') }}" method="get">
                                <input type="search" name="cari" value="{{ $cari }}" placeholder="Search...">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                        <div class="shop-content-wrapper">
                            <!--=======  shop header wrapper   =======-->
                            <div class="shop-product-wrap row grid">
                                @foreach ($list as $l)
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-custom-sm-6 col-12 img-full">
                                        <!--=======  grid view product  =======-->
                                        <div class="single-grid-product">
                                            <div class="single-grid-product__image"
                                                style="position: relative; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); ">
                                                <div class="product-badge-wrapper">
                                                    <span class="onsale">{{ $l->category }}</span>

                                                </div>
                                                <div class="product-badge-wrapper">

                                                </div>
                                                <a href="{{ url('product-detail/' . Crypt::encrypt($l->id)) }}"
                                                    class="image-wrap">
                                                    @if ($l->path_file && Storage::exists($l->path_file))
                                                        <img src="{{ url('produk-img/imageprimary/' . $l->id . '.png') }}"
                                                            class="img-fluid" alt=""
                                                            style="aspect-ratio: 1/1; object-fit: cover; width: 100%;">
                                                    @else
                                                        <img src="{{ asset('assets_frontend/img/noimage.png') }}"
                                                            class="img-fluid" alt=""
                                                            style="aspect-ratio: 1/1; object-fit: cover; width: 100%;">
                                                    @endif
                                                </a>

                                            </div>
                                            <div class="single-grid-product__content">
                                                <h3 class="title"><a
                                                        href="{{ url('product-detail/' . Crypt::encrypt($l->id)) }}">{{ \Illuminate\Support\Str::limit($l->name, 40) }}</a>
                                                </h3>
                                                <div class="price">
                                                    {{--                                                    <span --}}
                                                    {{--                                                        class="discounted-price">Rp {{ number_format($l->price, 0, ',', '.') }}</span> --}}
                                                </div>

                                                <div class="rating"
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div>
                                                        <i id="likeIcon{{ $l->id }}"
                                                            style="font-size: 15px; color: red"
                                                            onclick="toggleLike('{{ $l->id }}', {{ $l->islike ? 'true' : 'false' }}, {{ $l->likes }})"
                                                            class="fa {{ $l->islike ? 'fa-heart' : 'fa-heart-o' }}"></i>
                                                        <span
                                                            id="likeCount{{ $l->id }}">{{ $l->likes }}</span>
                                                        Suka

                                                    </div>
                                                    @php
                                                        $title = $l->name;
                                                        $fullname =
                                                            session('user')->first_name .
                                                            ' ' .
                                                            session('user')->last_name;
                                                        $gambar = url('produk-img/imageprimary/' . $l->id . '.png');

                                                        //                                                          var_dump($gambar);die();
                                                        $harga = number_format($l->price, 0, ',', '.');
                                                        $nomorWhatsApp = '+6281120209000';
                                                    @endphp


                                                    <a href="https://wa.me/<?php echo $nomorWhatsApp; ?>?text=<?php echo urlencode("Hallo, saya $fullname ingin menanyakan produk:\n\nTitle: $title\n"); ?>"
                                                        target="_blank" class="theme-button theme-button--alt btn-sm"
                                                        style="border-radius: 20px;">
                                                        <i class="fa fa-whatsapp" style="color: white"></i> Customer Care
                                                    </a>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                @endforeach

                            </div>

                            <!--=======  End of shop product wrapper  =======-->

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
                                        <li><a
                                                href="{{ $list->url(1) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">1</a>
                                        </li>
                                        <li class="disabled"><span>...</span></li>
                                    @endif

                                    @foreach (range(max(1, $list->currentPage() - 2), min($list->currentPage() + 2, $list->lastPage())) as $page)
                                        @if ($page == $list->currentPage())
                                            <li class="active"><span>{{ $page }}</span></li>
                                        @else
                                            <li>
                                                <a
                                                    href="{{ $list->url($page) }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
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
                                            <a
                                                href="{{ $list->nextPageUrl() }}{{ !empty($cari) ? '&cari=' . $cari : '' }}">
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

                        <!--=======  End of shop content wrapper  =======-->
                    </div>
                </div>
            </div>
        </div>

        <!--=======  End of shop page area  =======-->
    </div>
@endsection
@section('script')
    <!-- Di dalam Blade View atau template Anda -->
    <script>
        function toggleLike(id, isLiked, likes) {
            console.log(id);
            var isAuthenticated = true; // Ganti ini berdasarkan logika otentikasi Anda

            if (isAuthenticated) {
                // Toggle the like status
                isLiked = !isLiked;


                var likeIcon = document.getElementById("likeIcon" + id);
                var likeCount = document.getElementById("likeCount" + id);


                // Kirim permintaan AJAX menggunakan Fetch API
                fetch('/productlikes', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            postId: id,
                            isLiked: isLiked,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Tangani respons server jika diperlukan
                        console.log(data);

                        $('span#likeCount' + id).html(data.likes);
                        likeIcon.classList.remove('fa-heart', 'fa-heart-o');
                        likeIcon.classList.add(data.status == false ? 'fa-heart' : 'fa-heart-o');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                // Redirect the user to the login page or show a login modal
                alert('Anda harus login untuk memberikan suka.');
                // Anda dapat menambahkan logika untuk mengarahkan atau menampilkan modal login di sini
            }
        }
    </script>
@endsection
