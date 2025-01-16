<div>
    <div class="container container-240">
        <div class="single-product-detail product-bundle product-aff">
            <ul class="breadcrumb">
                {{-- <li><a href="#">Home</a></li>
                <li class="active">Accessories</li>
                <li class="active">Ultra Wireless S50 Headphones </li> --}}
            </ul>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">

                    <div class="flex product-img-slide">


                        <div class="product-images">
                            <div class="main-img js-product-slider">
                                <a href="#" class="hover-images effect"><img src="{{ $image_url }}"
                                        alt="photo" class="img-reponsive"></a>
                                @if (isset($image_url_2))
                                    <a href="#" class="hover-images effect"><img src="{{ $image_url_2 }}"
                                            alt="photo" class="img-reponsive"></a>
                                @endif
                                @if (isset($image_url_3))
                                    <a href="#" class="hover-images effect"><img src="{{ $image_url_3 }}"
                                            alt="photo" class="img-reponsive"></a>
                                @endif
                                @if (isset($image_url_4))
                                    <a href="#" class="hover-images effect"><img src="{{ $image_url_4 }}"
                                            alt="photo" class="img-reponsive"></a>
                                @endif
                                {{-- <a href="#" class="hover-images effect"><img src="img/single/sony4.jpg"
                                        alt="photo" class="img-reponsive"></a>
                                <a href="#" class="hover-images effect"><img src="img/single/sony4.jpg"
                                        alt="photo" class="img-reponsive"></a> --}}
                            </div>
                        </div>
                        <div class="multiple-img-list-ver2 js-click-product">
                            <div class="product-col">
                                <div class="img active">
                                    <img src="{{ $image_url }}" alt="photo" class="img-reponsive">
                                </div>
                            </div>
                            @if (isset($image_url_2))
                                <div class="product-col">
                                    <div class="img">
                                        <img src="{{ $image_url_2 }}" alt="images" class="img-responsive">
                                    </div>
                                </div>
                            @endif
                            @if (isset($image_url_3))
                                <div class="product-col">
                                    <div class="img">
                                        <img src="{{ $image_url_3 }}" alt="images" class="img-responsive">
                                    </div>
                                </div>
                            @endif
                            @if (isset($image_url_4))
                                <div class="product-col">
                                    <div class="img">
                                        <img src="{{ $image_url_4 }}" alt="images" class="img-responsive">
                                    </div>
                                </div>
                            @endif
                            {{-- <div class="product-col">
                                <div class="img">
                                    <img src="img/single/sony4.jpg" alt="images" class="img-responsive">
                                </div>
                            </div>
                            <div class="product-col">
                                <div class="img">
                                    <img src="img/single/sony4.jpg" alt="images" class="img-responsive">
                                </div>
                            </div> --}}
                        </div>
                    </div>


                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="single-flex">
                        <div class="single-product-info product-info product-grid-v2 s-50">
                            {{-- <p class="product-cate">Audio Speakers</p> --}}
                            {{-- <div class="product-rating">
                                <span class="star star-5"></span>
                                <span class="star star-4"></span>
                                <span class="star star-3"></span>
                                <span class="star star-2"></span>
                                <span class="star star-1"></span>
                                <div class="number-rating">( 896 reviews )</div>
                            </div> --}}
                            <h3 class="product-title"><a href="#">{{ $item->title }} </a></h3>
                            <div class="product-price">
                                <span>{{env('CURRENCY')}}{{ $total_price }}</span>
                            </div>
                            <div class="availability">
                                <p class="product-inventory"> <label>Availability : </label><span> In stock</span></p>
                            </div>
                            {{-- <div class="product-brand">
                                <p>Brand :</p>
                                <img src="img/single/sony_brand.png" alt="">
                            </div> --}}
                            <div class="product-sku">
                                <label>SKU :</label><span> {{ $item->sku }}</span>
                            </div>
                            <div class="short-desc">
                                <p class="product-desc">{{ $item->description }}</p>
                                {{-- <ul class="desc-list">
                                    <li>Connects directly to Bluetooth</li>
                                    <li>Battery Indicator light</li>
                                    <li>DPI Selection:2600/2000/1600/1200/800</li>
                                    <li>Computers running Windows</li>
                                </ul> --}}
                            </div>
                            @if (isset($item->colors) && count($item->colors) > 0)
                                <div class="color-group">
                                    <label>Availble Colors :</label>
                                    @foreach ($item->colors as $color)
                                        <a class="circle " wire:click='setColor({{ $color->color->id }})'
                                            style="background-color: {{ $color->color->code }};"></a>
                                    @endforeach

                                </div>
                                <div class="single-product-button-group ml-5">
                                    <label for="size">Color : </label>
                                    <div class="select-custom set-left-margin">
                                        <select name="color_id" wire:model='color_id' required
                                            class="form-control ml-5">
                                            <option value="">Select a color</option>
                                            @foreach ($item->colors as $color)
                                                <option value="{{ $color->color->id }}">{{ $color->color->name }}
                                                </option>
                                            @endforeach


                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if (isset($item->sizes) && count($item->sizes) > 0)
                                <div class="single-product-button-group ml-5">
                                    <label for="size">Available Sizes : </label>
                                    <div class="select-custom set-left-margin">
                                        <select name="size_id" wire:model='size_id' required wire:change='selectSize()'
                                            class="form-control getSizePrice">
                                            <option data-price="0" value="">Select a size</option>
                                            @foreach ($item->sizes as $size)
                                                <option data-price="{{ !empty($size->price) ? $size->price : 0 }}"
                                                    value="{{ $size->id }}">{{ $size->name }}
                                                    @if (!empty($size->price))
                                                        (Rs. {{ number_format($size->price, 2) }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="single-product-button-group">
                                <div class="e-btn cart-qtt btn-gradient">
                                    <div class="e-quantity">
                                        <input type="number" step="1" min="1" max="999"
                                            name="quantity" value="1" title="Qty"
                                            class="qty input-text js-number" size="4" wire:model='quantity'
                                            wire:change='setQty()'>
                                        <div class="tc pa">
                                            <a class="js-plus quantity-right-plus"><i class="fa fa-caret-up"></i></a>
                                            <a class="js-minus quantity-left-minus"><i
                                                    class="fa fa-caret-down"></i></a>
                                        </div>
                                    </div>
                                    <a wire:click='processAddToCart({{ $item->id }})'class="btn-add-cart"> <span
                                            class="icon-bg icon-cart v2"></span></a>
                                </div>
                                {{-- <a href="#" class="e-btn btn-icon">
                                    <span class="icon-bg icon-wishlist"></span>
                                </a>
                                <a href="#" class="e-btn btn-icon">
                                    <span class="icon-bg icon-compare"></span>
                                </a> --}}
                            </div>

                            {{-- <div class="product-tags">
                                <label>Tags :</label>
                                <a href="#">Fast,</a>
                                <a href="#">Gaming,</a>
                                <a href="#">Strong</a>
                            </div> --}}
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <style>
        .set-left-margin {
            margin-left: 5px;
        }
    </style>
</div>
