@extends('frontend.layout.master')

@section('title', 'Shop')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Shop</span></li>
            </ul>
        </section>

            <!-- AI Recommendations - Prominent placement -->
    <section class="container section">
        <div id="ai-recommendations-shop" class="ai-recommendations-section">
            <!-- Category-based recommendations -->
        </div>
    </section>

        <!--=============== PRODUCTS ===============-->
        <section class="products container section--lg">
            <section class="categories container section">
                <h2 class="text-center mb-5">
                    @if (isset($category))
                        Products in {{ $category->name }}
                    @else
                        All Products
                    @endif
                </h2>
                <div class="categories__container__filter swiper">
                    <div class="swiper-wrapper">
                        <!-- Category Filters -->
                        <div class="swiper-slide">
                            <a href="{{ route('shop') }}" class="btn ">All</a>
                        </div>
                        @if (isset($categories) && count($categories) > 0)
                            @foreach ($categories as $cat)
                                <div class="swiper-slide">
                                    <a href="{{ route('shop-category', $cat->slug) }}" class="btn category-btn"
                                        style="font-size: 12px;" title="{{ $cat->name }}">
                                        {{ \Illuminate\Support\Str::limit($cat->name, 12, '...') }}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="swiper-button-prev">

                    </div>
                    <div class="swiper-button-next">

                    </div>
                </div>
            </section>

            <div class="row">
                <div class="col-6">
                    <p class="total__products">We found <span>{{ $products->total() }}</span> items for you!</p>
                </div>
                <div class=" col-6">
                    <div class="sort-container">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle w-100" type="button" id="sortDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Sort Products
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li>
                                    <a class="dropdown-item sort-option {{ request('sort') == 'latest' || !request('sort') ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}">
                                        Latest
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item sort-option {{ request('sort') == 'low_to_high' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'low_to_high']) }}">
                                        Price: Low to High
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item sort-option {{ request('sort') == 'high_to_low' ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['sort' => 'high_to_low']) }}">
                                        Price: High to Low
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="products__container grid">
                @foreach ($products as $product)
                    <div class="product__item">
                        <div class="product__banner">
                            <a href="{{ route('product-details', parameters: $product->slug) }}" class="product__images">
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
                                <form action="{{ route('wishlist.add') }}" method="POST" class="wishlistForm d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="action__btn" id="wishlistBtn" aria-label="Wishlist">
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

            @if ($products->hasPages())
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if (!$products->onFirstPage())
                        <li>
                            <a href="{{ $products->previousPageUrl() }}" class="pagination__link icon">
                                <i class="fi-rs-angle-double-small-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Numbered Page Links --}}
                    @foreach ($products->onEachSide(1)->links()->elements[0] as $page => $url)
                        <li>
                            <a href="{{ $url }}"
                                class="pagination__link {{ $products->currentPage() == $page ? 'active' : '' }}">
                                {{ sprintf('%02d', $page) }}
                            </a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <li>
                            <a href="{{ $products->nextPageUrl() }}" class="pagination__link icon">
                                <i class="fi-rs-angle-double-small-right"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif
        </section>
    @endsection
    @push('scripts')
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
