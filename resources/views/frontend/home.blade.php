@extends('frontend.layout.master')

@section('title', 'Home')
@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== HOME ===============-->
        <section class="home section--lg">
            <div class="home__container container grid">
                <div class="home__content">
                    <span class="home__subtitle">Hot Promotions</span>
                    <h1 class="home__title">
                        Fashion Trending <span>Great Collection</span>
                    </h1>
                    <p class="home__description">
                        Save more with coupons & up tp 20% off
                    </p>
                    <a href="{{ route('shop') }}" class="btn">Shop Now</a>
                </div>
                <img src="{{ asset('assets/img/main-2.png') }}"ZZ class="home__img" alt="hats" />
            </div>
        </section>

        <!--=============== CATEGORIES ===============-->
        <section class="categories container section">
            <h3 class="section__title"><span>Popular</span> Categories</h3>
            <div class="categories__container swiper">
                <div class="swiper-wrapper">
                    @forelse ($userCategories as $category)
                        <a href="{{ route('shop-category', $category->slug) }}" class="category__item swiper-slide">
                            <img src="{{ asset('storage/' . $category->image) }}"alt="" class="category__img" />
                            <h3 class="category__title">{{ $category->name }}</h3>
                        </a>
                    @empty
                        <div colspan="7" class="text-center text-muted">No categories found.</div>
                    @endforelse

                </div>

                <div class="swiper-button-prev">

                </div>
                <div class="swiper-button-next">

                </div>
            </div>
        </section>

        <!-- AI Recommendations Section 1 - After Categories -->
        <section class="container section">
            <div id="ai-recommendations-home" class="ai-recommendations-section">
                <!-- AI will load personalized recommendations here -->
            </div>
        </section>

        <!--=============== PRODUCTS ===============-->
        <section class="products container section">
            <div class="tab__btns">
                <span class="tab__btn active-tab" data-target="#featured">Featured</span>
                <span class="tab__btn" data-target="#popular">Popular</span>
                <span class="tab__btn" data-target="#new-added">New Added</span>
            </div>

            <div class="tab__items">
                <div class="tab__item active-tab" content id="featured">
                    <div class="products__container grid">
                        @foreach ($userProducts as $product)
                            <div class="product__item">
                                <div class="product__banner">
                                    <a href="{{ route('product-details', $product->slug) }}" class="product__images">
                                        <img src="{{ asset('storage/' . $product->image_front) }}" alt=""
                                            class="product__img default" />
                                        <img src="{{ asset('storage/' . $product->image_back) }}" alt=""
                                            class="product__img hover" />
                                    </a>
                                    <div class="product__actions">
                                        <a href="{{ route('product-details', $product->slug) }}" class="action__btn"
                                            aria-label="Quick View">
                                            <i class="fi fi-rs-eye"></i>
                                        </a>
                                        <form action="{{ route('wishlist.add') }}" method="POST"
                                            class="wishlistForm d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="action__btn" id="wishlistBtn"
                                                aria-label="Wishlist">
                                                <i class="fi fi-rs-heart"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('addToCompare', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action__btn" aria-label="Compare">
                                                <i class="fi fi-rs-shuffle"></i>
                                            </button>
                                        </form>
                                    </div>
                                    {!! $product->discount_badge !!}
                                </div>
                                <div class="product__content">
                                    <span class="product__category">{{ $product->category->name }}</span>
                                    <a href="{{ route('product-details', $product->slug) }}">
                                        <h3 class="product__title">{{ $product->name }}</h3>
                                    </a>
                                    <div class="product__rating">
                                        <i class="fi fi-rs-star"></i>
                                        <i class="fi fi-rs-star"></i>
                                        <i class="fi fi-rs-star"></i>
                                        <i class="fi fi-rs-star"></i>
                                        <i class="fi fi-rs-star"></i>
                                    </div>
                                    <div class="product__price flex">
                                        <span class="new__price">{{ $product->discount_price }}</span>
                                        <span class="old__price">{{ $product->price }}</span>
                                    </div>
                                    {{-- <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                    <i class="fi fi-rs-shopping-bag-add"></i>
                                </a> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab__item" content id="popular">
                    <div class="products__container grid">
                        @foreach ($trendy as $product)
                            <div class="product__item">
                                <div class="product__banner">
                                    <a href="{{ route('detail', ['id' => $product->id]) }}" class="product__images">
                                        <img src="{{ asset('storage/' . $product->image_front) }}" alt=""
                                            class="product__img default" />
                                        <img src="{{ asset('storage/' . $product->image_back) }}" alt=""
                                            class="product__img hover" />
                                    </a>
                                    <div class="product__actions">
                                        <a href="{{ route('product-details', $product->slug) }}" class="action__btn"
                                            aria-label="Quick View">
                                            <i class="fi fi-rs-eye"></i>
                                        </a>
                                        <form action="{{ route('wishlist.add') }}" method="POST"
                                            class="wishlistForm d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="action__btn" id="wishlistBtn"
                                                aria-label="Wishlist">
                                                <i class="fi fi-rs-heart"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('addToCompare', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action__btn" aria-label="Compare">
                                                <i class="fi fi-rs-shuffle"></i>
                                            </button>
                                        </form>
                                    </div>
                                    {!! $product->discount_badge !!}
                                </div>
                                <div class="product__content">
                                    <span
                                        class="product__category">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                    <a href="{{ route('detail', ['id' => $product->id]) }}">
                                        <h3 class="product__title">{{ $product->name }}</h3>
                                    </a>
                                    <div class="product__rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fi fi-rs-star {{ $i <= $product->rating ? '' : 'inactive' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="product__price flex">
                                        <span
                                            class="new__price">${{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                                        @if ($product->discount_price)
                                            <span class="old__price">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab__item" content id="new-added">
                    <div class="products__container grid">
                        @foreach ($newArrivals as $product)
                            <div class="product__item">
                                <div class="product__banner">
                                    <a href="{{ route('detail', ['id' => $product->id]) }}" class="product__images">
                                        <img src="{{ asset('storage/' . $product->image_front) }}" alt=""
                                            class="product__img default" />
                                        <img src="{{ asset('storage/' . $product->image_back) }}" alt=""
                                            class="product__img hover" />
                                    </a>
                                    <div class="product__actions">
                                        <a href="{{ route('product-details', $product->slug) }}" class="action__btn"
                                            aria-label="Quick View">
                                            <i class="fi fi-rs-eye"></i>
                                        </a>
                                        <form action="{{ route('wishlist.add') }}" method="POST"
                                            class="wishlistForm d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="action__btn" id="wishlistBtn"
                                                aria-label="Wishlist">
                                                <i class="fi fi-rs-heart"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('addToCompare', $product->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action__btn" aria-label="Compare">
                                                <i class="fi fi-rs-shuffle"></i>
                                            </button>
                                        </form>
                                    </div>
                                    {!! $product->discount_badge !!}
                                </div>
                                <div class="product__content">
                                    <span
                                        class="product__category">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                    <a href="{{ route('detail', ['id' => $product->id]) }}">
                                        <h3 class="product__title">{{ $product->name }}</h3>
                                    </a>
                                    <div class="product__rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fi fi-rs-star {{ $i <= $product->rating ? '' : 'inactive' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="product__price flex">
                                        <span
                                            class="new__price">${{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                                        @if ($product->discount_price)
                                            <span class="old__price">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>

        <!--=============== DEALS ===============-->
        <section class="deals section">
            <div class="deals__container container grid">
                @foreach ($userdeals as $deal)
                    <div class="swiper-slide">
                        <div class="deals__item">
                            <div class="deals__group">
                                <h3 class="deals__brand">{{ $deal->deal_title }}</h3>
                                <span class="deals__category">
                                    {{ $deal->limited_quantities ? 'Limited quantities' : $deal->collection_type }}
                                </span>
                            </div>
                            <h4 class="deals__title">{{ $deal->offer_message }}</h4>
                            <div class="deals__price flex">
                                <span class="new__price">${{ number_format($deal->price, 2) }}</span>
                                <span class="old__price">${{ number_format($deal->original_price, 2) }}</span>
                            </div>
                            <div class="deals__group">
                                <p class="deals__countdown-text">Hurry Up! Offer Ends In:</p>
                                <div class="countdown" data-end-date="{{ $deal->end_date }}">
                                    <div class="countdown__amount">
                                        <p class="countdown__period">00</p><span class="unit">Days</span>
                                    </div>
                                    <div class="countdown__amount">
                                        <p class="countdown__period">00</p><span class="unit">Hours</span>
                                    </div>
                                    <div class="countdown__amount">
                                        <p class="countdown__period">00</p><span class="unit">Mins</span>
                                    </div>
                                    <div class="countdown__amount">
                                        <p class="countdown__period">00</p><span class="unit">Sec</span>
                                    </div>
                                </div>
                            </div>
                            <div class="deals__btn">
                                <a href="{{ route('detail') }}" class="btn btn--md">Shop Now</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>


        <!--=============== NEW ARRIVALS ===============-->
        <section class="new__arrivals container section">
            <h3 class="section__title"><span>New</span> Arrivals</h3>
            <div class="new__container swiper">
                <div class="swiper-wrapper">
                    @foreach ($newArrivals as $product)
                        <div class="product__item swiper-slide">
                            <div class="product__banner">
                                <a href="{{ route('product-details', $product->slug) }}" class="product__images">
                                    <img src="{{ asset('storage/' . $product->image_front) }}"
                                        alt="{{ $product->name }}" class="product__img default" />
                                    <img src="{{ asset('storage/' . $product->image_back) }}" alt="{{ $product->name }}"
                                        class="product__img hover" />
                                </a>
                                <div class="product__actions">
                                    <a href="{{ route('product-details', $product->slug) }}" class="action__btn"
                                        aria-label="Quick View">
                                        <i class="fi fi-rs-eye"></i>
                                    </a>
                                    <form action="{{ route('wishlist.add') }}" method="POST"
                                        class="wishlistForm d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="action__btn" id="wishlistBtn"
                                            aria-label="Wishlist">
                                            <i class="fi fi-rs-heart"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('addToCompare', $product->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action__btn" aria-label="Compare">
                                            <i class="fi fi-rs-shuffle"></i>
                                        </button>
                                    </form>
                                </div>
                                {!! $product->discount_badge !!}
                            </div>
                            <div class="product__content">
                                <span class="product__category">{{ $product->category->name }}</span>
                                <a href="{{ route('product-details', $product->slug) }}">
                                    <h3 class="product__title">{{ $product->name }}</h3>
                                </a>
                                <div class="product__rating">
                                    <i class="fi fi-rs-star"></i>
                                    <i class="fi fi-rs-star"></i>
                                    <i class="fi fi-rs-star"></i>
                                    <i class="fi fi-rs-star"></i>
                                    <i class="fi fi-rs-star"></i>
                                </div>
                                <div class="product__price flex">
                                    @if ($product->discount_price)
                                        <span class="new__price">${{ $product->discount_price }}</span>
                                        <span class="old__price">${{ $product->price }}</span>
                                    @else
                                        <span class="new__price">${{ $product->price }}</span>
                                    @endif
                                </div>
                                {{-- <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                    <i class="fi fi-rs-shopping-bag-add"></i>
                                </a> --}}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-prev">
                    <i class="fi fi-rs-angle-left"></i>
                </div>
                <div class="swiper-button-next">
                    <i class="fi fi-rs-angle-right"></i>
                </div>
            </div>
        </section>

        <!--=============== SHOWCASE ===============-->
        <section class="showcase section">
            <div class="showcase__container container grid">
                {{-- Hot Releases --}}
                <div class="showcase__wrapper">
                    <h3 class="section__title">Hot Releases</h3>
                    @foreach ($hotReleases as $product)
                        <div class="showcase__item">
                            <a href="{{ route('product-details', $product->slug) }}" class="showcase__img-box">
                                <img src="{{ asset('storage/' . $product->image_front) }}" alt="{{ $product->name }}"
                                    class="showcase__img" />
                            </a>
                            <div class="showcase__content">
                                <a href="{{ route('product-details', $product->slug) }}">
                                    <h4 class="showcase__title">{{ $product->name }}</h4>
                                </a>
                                <div class="showcase__price flex">
                                    <span class="new__price">${{ $product->discount_price }}</span>
                                    <span class="old__price">${{ $product->price }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Deals & Outlet --}}
                <div class="showcase__wrapper">
                    <h3 class="section__title">Deals & Outlet</h3>
                    @foreach ($deals as $product)
                        <div class="showcase__item">
                            <a href="{{ route('product-details', $product->slug) }}" class="showcase__img-box">
                                <img src="{{ asset('storage/' . $product->image_front) }}" alt="{{ $product->name }}"
                                    class="showcase__img" />
                            </a>
                            <div class="showcase__content">
                                <a href="{{ route('product-details', $product->slug) }}">
                                    <h4 class="showcase__title">{{ $product->name }}</h4>
                                </a>
                                <div class="showcase__price flex">
                                    <span class="new__price">${{ $product->discount_price }}</span>
                                    <span class="old__price">${{ $product->price }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Top Selling --}}
                <div class="showcase__wrapper">
                    <h3 class="section__title">Top Selling</h3>
                    @foreach ($topSelling as $product)
                        <div class="showcase__item">
                            <a href="{{ route('product-details', $product->slug) }}" class="showcase__img-box">
                                <img src="{{ asset('storage/' . $product->image_front) }}" alt="{{ $product->name }}"
                                    class="showcase__img" />
                            </a>
                            <div class="showcase__content">
                                <a href="{{ route('product-details', $product->slug) }}">
                                    <h4 class="showcase__title">{{ $product->name }}</h4>
                                </a>
                                <div class="showcase__price flex">
                                    @if ($product->discount_price)
                                        <span class="new__price">${{ $product->discount_price }}</span>
                                        <span class="old__price">${{ $product->price }}</span>
                                    @else
                                        <span class="new__price">${{ $product->price }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Trendy --}}
                <div class="showcase__wrapper">
                    <h3 class="section__title">Trendy</h3>
                    @foreach ($trendy as $product)
                        <div class="showcase__item">
                            <a href="{{ route('product-details', $product->slug) }}" class="showcase__img-box">
                                <img src="{{ asset('storage/' . $product->image_front) }}" alt="{{ $product->name }}"
                                    class="showcase__img" />
                            </a>
                            <div class="showcase__content">
                                <a href="{{ route('product-details', $product->slug) }}">
                                    <h4 class="showcase__title">{{ $product->name }}</h4>
                                </a>
                                <div class="showcase__price flex">
                                    @if ($product->discount_price)
                                        <span class="new__price">${{ $product->discount_price }}</span>
                                        <span class="old__price">${{ $product->price }}</span>
                                    @else
                                        <span class="new__price">${{ $product->price }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- AI Recommendations Section 2 - Trending -->
        <section class="container section">
            <div id="ai-recommendations-trending" class="ai-recommendations-section">
                <!-- AI will load trending recommendations here -->
            </div>
        </section>

    @endsection

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const countdownElements = document.querySelectorAll('.countdown');

                countdownElements.forEach(function(countdown) {
                    const endDate = new Date(countdown.getAttribute('data-end-date')).getTime();

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = endDate - now;

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        const periods = countdown.querySelectorAll('.countdown__period');
                        if (periods.length >= 4) {
                            periods[0].textContent = String(days).padStart(2, '0');
                            periods[1].textContent = String(hours).padStart(2, '0');
                            periods[2].textContent = String(minutes).padStart(2, '0');
                            periods[3].textContent = String(seconds).padStart(2, '0');
                        }

                        // Stop the timer when the countdown ends
                        if (distance < 0) {
                            clearInterval(timer);
                            periods.forEach(el => el.textContent = '00');
                        }
                    }

                    updateCountdown(); // Initial call
                    const timer = setInterval(updateCountdown, 1000);
                });
            });
        </script>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Compare',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    background: '#1e1e2f',
                    color: '#ffffff',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                    didClose: () => {
                        window.location.reload();
                    }
                });
            </script>
        @endif

        @if (session('info'))
            <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Already Added',
                    text: '{{ session('info') }}',
                    toast: true,
                    position: 'top-end',
                    background: '#1e1e2f',
                    color: '#ffffff',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                    didClose: () => {
                        window.location.reload();
                    }
                });
            </script>
        @endif

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
            // Handle wishlist button click
            $(document).on('submit', '.wishlistForm', function(e) {
                e.preventDefault(); // Prevent form submission

                // Check if the user is logged in
                @if (auth()->check())
                    // If logged in, submit the form to add to the wishlist
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
                @else
                    // If not logged in, show SweetAlert to prompt for login
                    Swal.fire({
                        title: 'Login Required',
                        text: "Please login to add products to your wishlist!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Login Now'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login-signup') }}"; // Redirect to login page
                        }
                    });
                @endif
            });
        </script>
    @endpush
