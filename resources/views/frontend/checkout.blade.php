@extends('frontend.layout.master')

@section('title', 'Checkout')

@section('content')
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="{{ route('home') }}" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Shop</span></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Checkout</span></li>
            </ul>
        </section>

        <!--=============== CHECKOUT ===============-->
        <section class="checkout section--lg">
            <div class="checkout__container container grid">

                <!-- Billing Details -->
                <div class="checkout__group">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h3 class="section__title">Billing Details</h3>
                    <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data"
                        class="form grid" id="checkout-form">

                        @csrf
                        <input type="text" name="name" placeholder="Name" class="form__input"
                            value="{{ old('name', auth()->user()->name ?? '') }}" required />
                        <input type="text" name="address" placeholder="Address" class="form__input"
                            value="{{ $orderAddress->address ?? '' }}" required />
                        <input type="text" name="city" placeholder="City" class="form__input"
                            value="{{ $orderAddress->city ?? '' }}" required />
                        <input type="text" name="country" placeholder="Country" class="form__input"
                            value="{{ $orderAddress->country ?? '' }}" required />
                        <input type="text" name="postcode" placeholder="Postcode" class="form__input"
                            value="{{ $orderAddress->postcode ?? '' }}" required />
                        <input type="text" name="phone" placeholder="Phone" class="form__input"
                            value="{{ $orderAddress->phone ?? '' }}" required />

                        <input type="email" name="email" placeholder="Email" class="form__input"
                            value="{{ auth()->user()->email ?? '' }}" required />
                        <textarea name="order_note" placeholder="Order Note" class="form__input textarea"></textarea>
                </div>

                <!-- Cart Totals -->
                <div class="checkout__group">
                    <h3 class="section__title">Cart Totals</h3>
                    <table class="order__table">
                        <thead>
                            <tr>
                                <th colspan="2">Products</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp

                            @foreach ($cartItems as $item)
                                @php
                                    $price = $item->product->discount_price ?? $item->product->price;
                                    $totalPrice = $price * $item->quantity;
                                    $subtotal += $totalPrice;
                                @endphp
                                <tr>
                                    <td>
                                        <img src="{{ $item->product->image_front ? asset('storage/' . $item->product->image_front) : asset('default-image.jpg') }}"
                                            alt="{{ $item->product->name }}" class="order__img" />
                                    </td>
                                    <td>
                                        <h3 class="table__title">{{ $item->product->name }}</h3>
                                        <p class="table__quantity">x {{ $item->quantity }}</p>
                                    </td>
                                    <td><span class="table__price">${{ number_format($totalPrice, 2) }}</span></td>
                                </tr>

                                <!-- Hidden Inputs to Send Cart Items -->
                                <input type="hidden" name="items[{{ $loop->index }}][product_id]"
                                    value="{{ $item->product->id }}">
                                <input type="hidden" name="items[{{ $loop->index }}][quantity]"
                                    value="{{ $item->quantity }}">
                                <input type="hidden" name="items[{{ $loop->index }}][price]"
                                    value="{{ $price }}">
                            @endforeach

                            <tr>
                                <td><span class="order__subtitle">Subtotal</span></td>
                                <td colspan="2"><span class="table__price">${{ number_format($subtotal, 2) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="order__subtitle">Shipping</span></td>
                                <td colspan="2"><span class="table__price">Free Shipping</span></td>
                            </tr>
                            <tr>
                                <td><span class="order__subtitle">Total</span></td>
                                <td colspan="2"><span
                                        class="order__grand-total">${{ number_format($subtotal, 2) }}</span></td>
                            </tr>

                        </tbody>
                    </table>

                    <!-- Payment Methods Section -->
                    <div class="checkout__group">
                        <h3 class="section__title" style="margin-top: 10px;">Payment Method</h3>

                        @foreach ($paymentMethods as $method)
                            <label class="payment-method" style="display: block; margin-bottom: 10px;">
                                <input type="radio" name="payment_method" value="{{ $method->name }}" required
                                    {{ old('payment_method') === $method->name ? 'checked' : '' }}
                                    class="payment-method-radio">

                                {{ $method->name }}
                            </label>
                        @endforeach

                        @error('payment_method')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="temp_order_id" id="temp_order_id" />
                    </div>

                    <div id="bank-transfer-details"
                        style="{{ old('payment_method') === 'Bank Transfer' ? '' : 'display: none;' }}"
                        class="bank-card-container">
                        <h3 class="section-title">Bank Transfer Payment Details</h3>

                        <!-- Credit Card Style Bank Details -->
                        <div class="bank-card">
                            <div class="bank-card-header">
                                <div>
                                    <div class="bank-card-label">Bank Name</div>
                                    <div class="bank-card-value">{{ $bankDetails['bank_name'] }}</div>
                                </div>
                                <div class="bank-card-logo">VISA</div>
                            </div>

                            <div class="account-number">
                                <div class="bank-card-label">Account Number</div>
                                <div class="bank-card-value">{{ $bankDetails['account_number'] }}</div>
                            </div>

                            <div class="bank-card-footer">
                                <div class="card-section">
                                    <div class="bank-card-label">Account Name</div>
                                    <div class="bank-card-value">{{ $bankDetails['account_name'] }}</div>
                                </div>
                                <div class="card-section">
                                    <div class="bank-card-label">IFSC Code</div>
                                    <div class="bank-card-value">{{ $bankDetails['ifsc_code'] }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Styled File Upload -->
                        <div class="file-upload-container">
                            <label for="payment_screenshot" class="file-upload-label">Upload Payment Screenshot</label>
                            <label for="payment_screenshot" class="file-upload-area">
                                <svg class="upload-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="upload-text">Click to upload screenshot</p>
                                <p class="upload-hint">PNG, JPG or JPEG</p>
                                <input type="file" name="payment_screenshot" id="payment_screenshot" accept="image/*"
                                    class="file-input">
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                    <input type="hidden" name="total" value="{{ $subtotal }}">
                    <input type="hidden" name="payment_method_type" id="selected-payment-method">

                    <!-- Submit Button (Inside Form) -->
                    <button type="submit" class="btn btn--md" id="place-order-btn">Place Order</button>
                    </form>
                    <!-- Closing Form Here -->
                </div>
            </div>

        </section>
    </main>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Add SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('payment_screenshot').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    let imgPreview = document.getElementById('imagePreview');
                    if (!imgPreview) {
                        imgPreview.style.width = '100%';
                        imgPreview.style.height = '100%';
                        imgPreview.style.objectFit = 'cover';
                        imgPreview.style.position = 'absolute';
                        imgPreview.style.top = '0';
                        imgPreview.style.left = '0';
                        document.querySelector('.file-upload-area').appendChild(imgPreview);
                    }
                    imgPreview.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        document.getElementById('payment_screenshot').addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create or update image preview
                    let imgPreview = document.getElementById('imgPreview');
                    if (!imgPreview) {
                        imgPreview = document.createElement('img');
                        imgPreview.id = 'imgPreview';
                        imgPreview.style.maxWidth = '100%';
                        imgPreview.style.marginTop = '10px';
                        event.target.parentElement.appendChild(imgPreview);
                    }
                    imgPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkout-form');

            // Generate unique temporary order ID if not already set
            if (!document.getElementById('temp_order_id').value) {
                const tempOrderId = 'temp_' + Math.random().toString(36).substr(2, 10).toUpperCase();
                document.getElementById('temp_order_id').value = tempOrderId;
            }

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const selectedMethod = document.querySelector('input[name="payment_method"]:checked')
                    ?.value;

                // Show loading indicator
                Swal.fire({
                    title: 'Processing',
                    text: 'Please wait while we process your order...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // For Stripe payment, handle via AJAX
                if (selectedMethod === 'Stripe') {
                    const formData = new FormData(form);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');

                    try {
                        // Submit the form with all data as JSON
                        const formDataObj = {};
                        formData.forEach((value, key) => {
                            // Special handling for items array
                            if (key.includes('[')) {
                                const matches = key.match(/^([^\[]+)\[(\d+)\]\[([^\]]+)\]$/);
                                if (matches) {
                                    const [_, arrayName, index, property] = matches;

                                    if (!formDataObj[arrayName]) formDataObj[arrayName] = [];
                                    if (!formDataObj[arrayName][index]) formDataObj[arrayName][
                                        index
                                    ] = {};

                                    formDataObj[arrayName][index][property] = value;
                                } else {
                                    formDataObj[key] = value;
                                }
                            } else {
                                formDataObj[key] = value;
                            }
                        });

                        // Convert items object to array
                        if (formDataObj.items) {
                            formDataObj.items = Object.values(formDataObj.items);
                        }

                        // Submit the form data to create/update order
                        const response = await fetch('/checkout/process', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(formDataObj)
                        });

                        const result = await response.json();

                        if (result.url) {
                            // Success - redirect to Stripe checkout
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Redirecting to payment gateway...',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = result.url;
                            });
                        } else if (result.error) {
                            // Error from server
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.error,
                                confirmButtonText: 'Try Again'
                            });
                        } else {
                            // Unknown error
                            Swal.fire({
                                icon: 'warning',
                                title: 'Something went wrong',
                                text: 'Please try again or contact support.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } catch (error) {
                        console.error('Error during checkout:', error);

                        // Client-side error
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'An error occurred during checkout. Please check your internet connection and try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                } else {
                    // For non-Stripe payments (Bank Transfer or COD)
                    // Submit the form with validation
                    try {
                        const formData = new FormData(form);
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');

                        const response = await fetch('/checkout/process', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (response.redirected) {
                            // Success case - follow redirect
                            window.location.href = response.url;
                            return;
                        }

                        const result = await response.json();

                        if (response.ok) {
                            // Success but not redirected
                            Swal.fire({
                                icon: 'success',
                                title: 'Order Placed Successfully!',
                                text: 'Thank you for your order.',
                                confirmButtonText: 'Continue'
                            }).then(() => {
                                window.location.href = '/'; // Redirect to orders page
                            });
                        } else {
                            // Server validation errors or other issues
                            let errorMessage = 'Please check the form and try again.';

                            if (result.errors) {
                                // Format validation errors
                                const errorList = Object.values(result.errors).flat();
                                errorMessage = errorList.join('<br>');
                            } else if (result.message) {
                                errorMessage = result.message;
                            } else if (result.error) {
                                errorMessage = result.error;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: errorMessage,
                                confirmButtonText: 'Try Again'
                            });
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);

                        Swal.fire({
                            icon: 'error',
                            title: 'Connection Error',
                            text: 'An error occurred. Please check your internet connection and try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });

            // Show/hide bank transfer details based on payment method selection
            document.querySelectorAll('input[name="payment_method"]').forEach(function(input) {
                input.addEventListener('change', function() {
                    const screenshotInput = document.getElementById('payment_screenshot');
                    if (this.value === 'Bank Transfer') {
                        document.getElementById('bank-transfer-details').style.display = 'block';
                        screenshotInput.setAttribute('required', true);
                    } else {
                        document.getElementById('bank-transfer-details').style.display = 'none';
                        screenshotInput.removeAttribute('required');
                    }
                });
            });
        });
    </script>
    <!-- In your main layout file or specific views -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for flash messages and display with SweetAlert
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
@endpush
