@extends('frontend.layout.master')

@section('title', 'Details')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Fashion</span></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Henley Shirt</span></li>
            </ul>
        </section>

        <!--=============== DETAILS ===============-->
        <section class="details section--lg">
            <div class="details__container container grid">
                <div class="details__group">
                    <img src="{{ asset('storage/' . $product->image_front) }}" alt="" class="details__img" />
                    <div class="details__small-images grid">
                        <img src="{{ asset('storage/' . $product->image_back) }}" alt=""
                            class="details__small-img" />
                        <img src="{{ asset('storage/' . $product->image_front) }}" alt=""
                            class="details__small-img" />
                        <img src="{{ asset('storage/' . $product->image_back) }}" alt=""
                            class="details__small-img" />
                    </div>
                </div>
                <div class="details__group">
                    <h3 class="details__title">{{ $product->name }}</h3>
                    <p class="details__brand">Brand: <span>{{ $product->category->name }}</span></p>
                    <div class="details__price flex">
                        <span class="new__price">{{ $product->discount_price }}</span>
                        <span class="old__price">{{ $product->price }}</span>
                    </div>
                    <p class="short__description">
                        {{ $product->description }}
                    </p>

                    <div class="details__action">
                        {{-- <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" class="quantity" value="1" 
                                min="1" max="{{ $product->stock }}" 
                                {{ $product->stock < 1 ? 'disabled' : '' }} />
                        
                            <button type="submit" class="btn btn--sm" >
                                Add To Cart
                            </button>
                        </form> --}}

                        <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="number" name="quantity" class="quantity" value="1" min="1"
                                max="{{ $product->stock }}" {{ $product->stock < 1 ? 'disabled' : '' }} />

                            @if (auth()->check())
                                <button type="submit" class="btn btn--sm">Add To Cart</button>
                            @else
                                <a href="{{ route('login-signup') }}" class="btn btn--sm">Login to Add in Cart</a>
                            @endif
                        </form>


                        <form action="{{ route('wishlist.add') }}" method="POST" class="wishlistForm d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @if (auth()->check())
                                <button type="submit" class="details__action-btn">
                                    <i class="fi fi-rs-heart"></i>
                                </button>
                            @else
                                <a href="{{ route('login-signup') }}" class="btn btn--sm">Login to Add in Wishlist</a>
                            @endif
                        </form>

                    </div>
                    <ul class="details__meta">
                        <li class="meta__list flex">
                            <span>SKU:</span>{{ $product->sku ?? 'N/A' }}
                        </li>
                        <li class="meta__list flex">
                            <span>Tags:</span>{{ $product->tags ?? 'N/A' }}
                        </li>
                        <li class="meta__list flex">
                            <span>Availability:</span>{{ $product->stock > 0 ? $product->stock . ' Items in Stock' : 'Out of Stock' }}
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!--=============== DETAILS TAB ===============-->
        <section class="details__tab container">
            <div class="detail__tabs">
                <span class="detail__tab" data-target="#reviews">Reviews({{ $product->reviews->count() }})</span>
            </div>

            <div class="details__tabs-content">
                <div class="details__tab-content" id="reviews">
                    <div class="reviews__container grid">

                        <div id="reviews-container">
                            @foreach ($product->reviews as $index => $review)
                                <div class="review__single" style="{{ $index >= 5 ? 'display: none;' : '' }}">
                                    <div class="review-width">
                                        <img src="{{ Auth::user() && Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('assets/img/default-avatar.jpg') }}"
                                            alt="Profile Image" class="review__img" />



                                        <h4 class="review__title">{{ $review->name }}</h4>
                                    </div>
                                    <div class="review__data">
                                        <div class="review__rating">
                                            @for ($i = 0; $i < $review->rating; $i++)
                                                <i class="fi fi-rs-star"></i>
                                            @endfor
                                        </div>
                                        <p class="review__description">{{ $review->comment }}</p>
                                        <span
                                            class="review__date">{{ $review->created_at->format('F d, Y \a\t h:i A') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($product->reviews->count() > 5)
                            <div class="text-center mt-3">
                                <a href="javascript:void(0);" id="see-more-reviews" class="btn btn-primary">See More</a>
                            </div>
                        @endif


                    </div>

                    @if(auth()->check() && auth()->user()->orders()->whereHas('items', function($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })->exists())
                    
                        <div class="review__form">
                            <h4 class="review__form-title">Add a review</h4>
                    
                            <form action="{{ route('reviews.store') }}" method="POST" class="form grid">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                                <div class="rate__product">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required />
                                        <label for="star{{ $i }}"><i class="fi fi-rs-star"></i></label>
                                    @endfor
                                </div>
                    
                                <textarea name="comment" class="form__input textarea" placeholder="Write Comment" required></textarea>
                    
                                <div class="form__group grid">
                                    <input type="text" name="name" placeholder="Name" class="form__input" required>
                                    <input type="email" name="email" placeholder="Email" class="form__input" required>
                                </div>
                    
                                <div class="form__btn">
                                    <button class="btn" type="submit">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    
                        @else
                        <p class="text-center">You must purchase this product before leaving a review.</p>
                    @endif
                    
                    

                </div>
            </div>
        </section>


        <!--=============== PRODUCTS ===============-->
        <section class="products container section--lg">
            <h3 class="section__title"><span>Related</span> Products</h3>
            <div class="products__container grid">
                @foreach ($relatedProduct as $item)
                    <div class="product__item">
                        <div class="product__banner">
                            <a href="{{ route('product-details', $item->slug) }}" class="product__images">
                                <img src="{{ asset('storage/' . $item->image_front) }}" alt="{{ $item->name }}"
                                    class="product__img default" />
                                <img src="{{ asset('storage/' . $item->image_back) }}" alt="{{ $item->name }}"
                                    class="product__img hover" />
                            </a>
                            <div class="product__actions">
                                <a href="{{ route('product-details', $item->slug) }}" class="action__btn"
                                    aria-label="Quick View">
                                    <i class="fi fi-rs-eye"></i>
                                </a>
                                <a href="#" class="action__btn" aria-label="Add to Wishlist">
                                    <i class="fi fi-rs-heart"></i>
                                </a>
                                <a href="#" class="action__btn" aria-label="Compare">
                                    <i class="fi fi-rs-shuffle"></i>
                                </a>
                            </div>
                            @if ($item->discount_price)
                                <div class="product__badge light-pink">Hot</div>
                            @endif
                        </div>
                        <div class="product__content">
                            <span class="product__category">{{ $item->category->name }}</span>
                            <a href="{{ route('product-details', $item->slug) }}">
                                <h3 class="product__title">{{ $item->name }}</h3>
                            </a>
                            <div class="product__rating">
                                <i class="fi fi-rs-star"></i>
                                <i class="fi fi-rs-star"></i>
                                <i class="fi fi-rs-star"></i>
                                <i class="fi fi-rs-star"></i>
                                <i class="fi fi-rs-star"></i>
                            </div>
                            <div class="product__price flex">
                                @if ($item->discount_price)
                                    <span class="new__price">${{ $item->discount_price }}</span>
                                    <span class="old__price">${{ $item->price }}</span>
                                @else
                                    <span class="new__price">${{ $item->price }}</span>
                                @endif
                            </div>
                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class="fi fi-rs-shopping-bag-add"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

            <!-- AI Recommendations - Prominent placement -->
    <section class="container section">
        <div id="ai-recommendations-shop" class="ai-recommendations-section">
            <!-- Category-based recommendations -->
        </div>
    </section>
    @endsection
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const defaultTab = document.querySelector('[data-target="#reviews"]');
                if (defaultTab) {
                    defaultTab.click();
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const seeMoreBtn = document.getElementById('see-more-reviews');
                if (seeMoreBtn) {
                    seeMoreBtn.addEventListener('click', function() {
                        document.querySelectorAll('#reviews-container .review__single').forEach(function(
                            review) {
                            review.style.display = 'block';
                        });
                        seeMoreBtn.style.display = 'none';
                    });
                }
            });
        </script>

        <script>
            // Function to update wishlist count dynamically
            function updateWishlistCount() {
                $.ajax({
                    url: "{{ route('wishlist.count') }}",
                    type: "GET",
                    success: function(response) {
                        $('#wishlistCount').text(response.wishlistCount);
                    },
                    error: function() {
                        console.log("Error updating wishlist count.");
                    }
                });
            }

            // Function to update cart count dynamically
            function updateCartCount() {
                $.ajax({
                    url: "{{ route('cart.count') }}",
                    type: "GET",
                    success: function(response) {
                        $('#cartCount').text(response.cartCount);
                    },
                    error: function() {
                        console.log("Error updating cart count.");
                    }
                });
            }

            // AJAX request for adding to wishlist
            $(document).on('submit', '.wishlistForm', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        updateWishlistCount();
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            icon: 'success',
                            position: 'bottom-right'
                        });
                    },
                    error: function(xhr) {
                        var errorMessage = "Something went wrong.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $.toast({
                            heading: 'Error',
                            text: errorMessage,
                            icon: 'error',
                            position: 'bottom-right'
                        });
                    }
                });
            });

            // AJAX request for adding to cart
            $(document).on('submit', '#addToCartForm', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        updateCartCount();
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            icon: 'success',
                            position: 'bottom-right'
                        });
                    },
                    error: function(xhr) {
                        var errorMessage = "Something went wrong.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $.toast({
                            heading: 'Error',
                            text: errorMessage,
                            icon: 'error',
                            position: 'bottom-right'
                        });
                    }
                });
            });
        </script>
    @endpush
