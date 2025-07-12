@extends('frontend.layout.master')

@section('title', 'Cart')

@section('content')
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Shop</span></li>
                <li><span class="breadcrumb__link"></span>></li>
                <li><span class="breadcrumb__link">Cart</span></li>
            </ul>
        </section>

        <!--=============== CART ===============-->
        <section class="cart section--lg container">
            <div class="table__container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
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
                                    <span
                                        class="table__price">${{ $item->product->discount_price ?? $item->product->price }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Quantity Input (Initially Disabled) -->
                                    <input type="number" value="{{ $item->quantity }}" class="quantity" min="1"
                                        max="{{ $item->product->stock }}" data-cart-id="{{ $item->id }}" disabled />
                                </td>
                                <td>
                                    <span
                                        class="subtotal">${{ ($item->product->discount_price ?? $item->product->price) * $item->quantity }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-link delete-btn">
                                            <i class="fi fi-rs-trash table__trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart__actions">

                <!-- Update Cart Button (Initially for Edit) -->
                <a href="#" class="btn flex btn__md update-cart" data-mode="edit">
                    <i class="fi-rs-pencil"></i> Edit Cart
                </a>

                <a href="{{ route('shop') }}" class="btn flex btn__md">
                    <i class="fi-rs-shopping-bag"></i> Continue Shopping
                </a>
            </div>

            <div class="divider">
                <i class="fi fi-rs-fingerprint"></i>
            </div>

            <div class="cart__group grid">
                <div class="cart__total">
                    <h3 class="section__title">Cart Totals</h3>
                    <table class="cart__total-table">
                        <tr>
                            <td><span class="cart__total-title">Cart Subtotal</span></td>
                            <td><span
                                    class="cart__total-price">${{ $cartItems->sum(function ($item) {
                                        return ($item->product->discount_price ?? $item->product->price) * $item->quantity;
                                    }) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="cart__total-title">Total</span></td>
                            <td><span
                                    class="cart__total-price">${{ $cartItems->sum(function ($item) {
                                        return ($item->product->discount_price ?? $item->product->price) * $item->quantity;
                                    }) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    <a href="{{ route('checkout') }}" class="btn flex btn--md">
                        <i class="fi fi-rs-box-alt"></i> Proceed To Checkout
                    </a>
                </div>
            </div>
        </section>

            <!-- AI Recommendations - Frequently Bought Together -->
    <section class="container section">
        <div id="ai-recommendations-cart" class="ai-recommendations-section">
            <!-- Complementary product recommendations -->
        </div>
    </section>

    @endsection
    @push('scripts')
        <!-- Include SweetAlert if not already included -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $(document).on("click", ".update-cart", function(e) {
                    e.preventDefault();

                    var button = $(this);
                    var mode = button.attr("data-mode");

                    if (mode === "edit") {

                        $(".quantity").prop("disabled", false).focus();

                        button.html('<i class="fi-rs-shuffle"></i> Save Cart').attr("data-mode", "save");
                    } else {

                        var cartData = [];
                        $(".quantity").each(function() {
                            var cartId = $(this).data("cart-id");
                            var quantity = $(this).val();
                            cartData.push({
                                cart_id: cartId,
                                quantity: quantity
                            });
                        });

                        $.ajax({
                            url: "{{ route('cart.update') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                cartItems: cartData
                            },
                            success: function(response) {
                                Swal.fire("Success", response.message, "success");

                                $(".quantity").prop("disabled", true);
                                button.html('<i class="fi-rs-pencil"></i> Edit Cart').attr(
                                    "data-mode", "edit");
                            },
                            error: function(xhr) {
                                Swal.fire("Error", xhr.responseJSON.message, "error");
                            }
                        });
                    }
                });
            });
        </script>
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
        </script>
    @endpush
