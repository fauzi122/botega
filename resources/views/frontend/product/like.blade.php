@extends('frontend.widget.template')

@section('content')
    <div class="breadcrumb-area section-space--breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">

                    <div class="breadcrumb-wrapper">
                        <h2 class="page-title">Like</h2>
                        <ul class="breadcrumb-list">
                            <li><a href="{{url('home')}}">Home</a></li>
                            <li class="active">Like</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content-wrapper">
        <!--=======  shopping cart wrapper  =======-->

        <div class="shopping-cart-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!--=======  cart table  =======-->

                        <div class="cart-table-container">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th class="product-name" colspan="2">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product-quantity">Quantity</th>
                                        <th class="product-quantity">Stock Status</th>
                                        <th class="product-subtotal">&nbsp;</th>
                                        <th class="product-remove">&nbsp;</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @for ($i=0;$i<5;$i++)

                                    <tr>
                                        <td class="product-thumbnail">
                                            <a href="product-details-basic.html">
                                                <img src="assets_frontend/img/products/product-9-1-600x800.jpg" class="img-fluid" alt="" style="border-radius: 10px">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <a href="product-details-basic.html">Black Colored Light</a>
                                            <span class="product-variation">Color: Black</span>
                                        </td>

                                        <td class="product-price"><span class="price">$100.00</span></td>

                                        <td class="product-quantity">
                                            <div class="pro-qty d-inline-block mx-0">
                                                <input type="text" value="1">
                                            </div>
                                        </td>

                                        <td class="stock-status">
                                            <span class="stock-stat-message">IN STOCK</span>
                                        </td>

                                        <td class="add-to-cart"><button class="theme-button theme-button--alt theme-button--wishlist">ADD TO CART</button></td>

                                        <td class="product-remove">
                                            <a href="#">
                                                <i class="pe-7s-close"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!--=======  End of cart table  =======-->
                    </div>

                </div>
            </div>
        </div>

        <!--=======  End of shopping cart wrapper  =======-->
    </div>


@endsection

