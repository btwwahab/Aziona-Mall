@extends('frontend.layout.master')

@section('title', 'Wishlist')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Shop</span></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Wishlist</span></li>
            </ul>
        </section>

        <!--=============== WISHLIST ===============-->
        <section class="wishlist section--lg container">
            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock Status</th>
                            <th>Action</th>
                            <th>Rename</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tbody>
                        @forelse ($wishlistItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $item->product->image_front) }}" alt=""
                                        class="table__img" />
                                </td>
                                <td>
                                    <h3 class="table__title">{{ $item->product->name }}</h3>
                                    {{-- <p class="table__description">{{ $item->product->description }}</p> --}}
                                </td>
                                <td>
                                    <span class="table__price">${{ $item->product->price }}</span>
                                </td>
                                <td>
                                    <span class="table__stock">
                                        {{ $item->product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </td>
                                <td>
                                    <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                        <input type="number" name="quantity" class="quantity" value="1" min="1"
                                            max="{{ $item->product->stock }}"
                                            {{ $item->product->stock < 1 ? 'disabled' : '' }} />

                                            <button type="submit" class="btn btn--sm"><i class="fas fa-shopping-cart"></i>
                                            </button>
                                        
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('wishlist.remove', $item->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-link delete-btn">
                                            <i class="fi fi-rs-trash table__trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No items in wishlist</td>
                            </tr>
                        @endforelse
                    </tbody>

                    </tbody>
                </table>
            </div>
        </section>
    @endsection
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll(".delete-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        Swal.fire({
                            title: "Are you sure?",
                            text: "You won't be able to revert this!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, delete it!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.closest("form").submit();
                            }
                        });
                    });
                });
            });

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
