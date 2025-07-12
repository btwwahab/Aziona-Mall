@extends('frontend.layout.master')

@section('title', 'Compare')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Shop</span></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Compare</span></li>
            </ul>
        </section>

        <!--=============== COMPARE ===============-->
        <section class="compare container section--lg">
            @if ($products->count())
                <table class="compare__table">
                    <tr>
                        <th>Image</th>
                        @foreach ($products as $product)
                            <td>
                                <img src="{{ asset('storage/' . $product->image_front) }}" alt="{{ $product->name }}"
                                    class="compare__img" />
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Name</th>
                        @foreach ($products as $product)
                            <td>
                                <h3 class="table__title">{{ $product->name }}</h3>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Price</th>
                        @foreach ($products as $product)
                            <td><span class="table__price">${{ $product->discount_price ?? $product->price }}</span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Description</th>
                        @foreach ($products as $product)
                            <td>
                                <p>{{ $product->description }}</p>
                            </td>
                        @endforeach
                    </tr>
                    {{-- <tr>
                        <th>Colors</th>
                        @if (is_array($product->colors) && count($product->colors))
                        <ul class="color__list compare__colors">
                            @foreach ($product->colors as $color)
                                <li>
                                    <a href="#" class="color__link" style="background-color: {{ $color }}"></a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    </tr> --}}
                    <tr>
                        <th>Stock</th>
                        @foreach ($products as $product)
                            <td>
                                <span class="table__stock">
                                    {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
                                </span>
                            </td>
                        @endforeach
                    </tr>
                    {{-- <tr>
                        <th>Weight</th>
                        @foreach ($products as $product)
                            <td><span class="table__weight">{{ $product->weight ?? 'N/A' }}</span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Dimensions</th>
                        @foreach ($products as $product)
                            <td><span class="table__dimension">{{ $product->dimensions ?? 'N/A' }}</span></td>
                        @endforeach
                    </tr> --}}
                    <tr>
                        <th>Buy</th>
                        @foreach ($products as $product)
                            <td>
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
                        @endforeach
                    </tr>
                    <tr>
                        <th>Remove</th>
                        @foreach ($products as $product)
                            <td>
                                <form action="{{ route('removeFromCompare', $product->id) }}" method="POST"
                                    class="remove-compare-form">
                                    @csrf
                                    <button type="button" class="remove-compare-btn" aria-label="Remove">
                                        <i class="fi fi-rs-trash table__trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endforeach
                    </tr>
                </table>
            @else
                <p>No products to compare.</p>
            @endif

        </section>
    @endsection
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>

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
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    background: '#1e1e2f',
                    color: '#ffffff',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.reload();
                    }
                });
            </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.remove-compare-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You want to remove this item from compare?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, remove it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.closest('form').submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
