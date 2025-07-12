@extends('frontend.layout.master')

@section('title', 'Accounts')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Account</span></li>
            </ul>
        </section>

        <!--=============== ACCOUNTS ===============-->
        <section class="accounts section--lg">
            <div class="accounts__container container grid">
                <div class="account__tabs">
                    <p class="account__tab active-tab" data-target="#dashboard">
                        <i class="fi fi-rs-settings-sliders"></i> Dashboard
                    </p>
                    <p class="account__tab" data-target="#orders">
                        <i class="fi fi-rs-shopping-bag"></i> Orders
                    </p>
                    <p class="account__tab" data-target="#update-profile">
                        <i class="fi fi-rs-user"></i> Update Profile
                    </p>
                    <p class="account__tab" data-target="#address">
                        <i class="fi fi-rs-marker"></i> My Address
                    </p>
                    <p class="account__tab" data-target="#change-password">
                        <i class="fi fi-rs-settings-sliders"></i> Change Password
                    </p>
                    <form action="{{ route('user-logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="account__tab"><i class="fi fi-rs-exit"></i> Logout</button>
                    </form>
                </div>

                <div class="tabs__content">
                    @if (session('success'))
                        <div class="alert alert-success" style="position: relative; padding-right: 30px;">
                            <span class="close-btn"
                                style="cursor: pointer; position: absolute; top: 10px; right: 10px; font-size: 18px; font-weight: bold;"
                                onclick="this.parentElement.style.display='none';">&times;</span>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="tab__content active-tab" content id="dashboard">
                        <h3 class="tab__header">Hello, {{ Auth::user()->name ?? 'User' }} 👋</h3>
                        <div class="tab__body">
                            <p class="tab__description">
                                From your account dashboard. you can easily check & view your
                                recent order, manage your shipping and billing addresses and
                                edit your password and account details
                            </p>
                        </div>
                    </div>
                    <div class="tab__content" content id="orders">
                        <h3 class="tab__header">Your Orders</h3>
                        <div class="tab__body">
                            <table class="placed__order-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        @foreach ($order->items as $index => $item)
                                            <tr>
                                                @if ($index === 0)
                                                    <td rowspan="{{ $order->items->count() }}">#{{ $order->temp_order_id }}
                                                    </td>
                                                @endif
                                                <td>
                                                    @if ($item->product && $item->product->image_front)
                                                        <img src="{{ asset('storage/' . $item->product->image_front) }}"
                                                            alt="{{ $item->product->name }}"
                                                            style="width: 60px; height: auto;">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>${{ number_format($item->price, 2) }}</td>
                                                @if ($index === 0)
                                                    <td rowspan="{{ $order->items->count() }}">
                                                        {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}
                                                    </td>
                                                    <td rowspan="{{ $order->items->count() }}">
                                                        {{ ucfirst($order->status) }}</td>
                                                    <td rowspan="{{ $order->items->count() }}">
                                                        ${{ number_format($order->total, 2) }}</td>
                                                    <td rowspan="{{ $order->items->count() }}">
                                                        <a href="{{ route('order.view', $order->id) }}"
                                                            class="view__order">View</a>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="9">You haven't placed any orders yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab__content" content id="update-profile">
                        <h3 class="tab__header">Update Profile</h3>
                        <div class="tab__body">
                            <form class="form grid" method="POST" action="{{ route('profile.update.post') }}"
                                enctype="multipart/form-data" style="display: grid; gap: 1.5rem; max-width: 500px;">
                                @csrf

                                <!-- Profile Image Section -->
                                <div class="profile-image-wrapper"
                                    style="position: relative; width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; overflow: hidden;">

                                    <!-- Profile Image -->
                                    <img id="profile-image-preview"
                                        src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('admin-assets/images/users/avatar-7.jpg') }}"
                                        alt="Profile Image" class="profile-image"
                                        style="width: 100%; height: 100%; object-fit: cover;">


                                    <!-- Edit Icon (Font Awesome) -->
                                    <label for="profile_image"
                                        style="position: absolute; bottom: 8px; right: 8px; background-color: #ffffff; border-radius: 50%; padding: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); cursor: pointer; transition: background-color 0.3s ease-in-out;">
                                        <i class="fas fa-edit" style="font-size: 18px; color: #333;"></i>
                                    </label>
                                </div>

                                <!-- Hidden Input for Upload -->
                                <input type="file" id="profile_image" name="profile_image" accept="image/*"
                                    style="display: none;" onchange="previewImage(event)">

                                <!-- Error Display -->
                                @error('profile_image')
                                    <span class="error-text"
                                        style="color: red; font-size: 14px; display: block; text-align: center; margin-top: 10px; font-weight: bold;">
                                        {{ $message }}
                                    </span>
                                @enderror

                                <!-- Name Field -->
                                <div class="form__group" style="display: flex; flex-direction: column;">
                                    <label for="name" class="form__label"
                                        style="font-weight: 600; margin-bottom: 5px;">Name:</label>
                                    <input type="text" id="name" name="name" placeholder="Enter your name"
                                        class="form__input"
                                        style="padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 16px;"
                                        value="{{ old('name', $user->name) }}" required />
                                    @error('name')
                                        <span class="error-text"
                                            style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div class="form__group" style="display: flex; flex-direction: column;">
                                    <label for="email" class="form__label"
                                        style="font-weight: 600; margin-bottom: 5px;">Email:</label>
                                    <input type="email" id="email" name="email" placeholder="Enter your email"
                                        class="form__input"
                                        style="padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 16px;"
                                        value="{{ old('email', $user->email) }}" required />
                                    @error('email')
                                        <span class="error-text"
                                            style="color: red; font-size: 14px; margin-top: 5px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="form__btn">
                                    <button type="submit" class="btn btn--md">
                                        Save
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="tab__content" content id="address">
                        <h3 class="tab__header">Shipping Address</h3>
                        <div class="tab__body">

                            @if ($orderAddress)
                                <!-- Address View -->
                                <div id="address-view">
                                    <address class="address"
                                        style="background-color: #f9f9f9; border: 1px solid #ddd; padding: 15px; border-radius: 5px; font-style: normal; max-width: 400px; line-height: 1.6; margin-bottom: 20px; font-family: Arial, sans-serif; color: #333;">

                                        <strong style="display: block; margin-bottom: 5px;">Address:</strong>
                                        <span id="address-text"
                                            style="display: block; margin-bottom: 10px;">{{ $orderAddress->address ?? 'N/A' }}</span>

                                        <strong style="display: block; margin-bottom: 5px;">City:</strong>
                                        <span id="city-text"
                                            style="display: block; margin-bottom: 10px;">{{ $orderAddress->city ?? 'N/A' }}</span>

                                        <strong style="display: block; margin-bottom: 5px;">Country:</strong>
                                        <span id="country-text"
                                            style="display: block; margin-bottom: 10px;">{{ $orderAddress->country ?? 'N/A' }}</span>

                                        <strong style="display: block; margin-bottom: 5px;">Postcode:</strong>
                                        <span id="postcode-text"
                                            style="display: block; margin-bottom: 10px;">{{ $orderAddress->postcode ?? 'N/A' }}</span>

                                        <strong style="display: block; margin-bottom: 5px;">Phone:</strong>
                                        <span id="phone-text"
                                            style="display: block;">{{ $orderAddress->phone ?? 'N/A' }}</span>

                                    </address>


                                    <a href="#" id="edit-button" class="edit"
                                        style="color: #007bff; text-decoration: underline;">Edit</a>
                                </div>
                                <!-- Edit Form (Hidden) -->
                                <div id="address-edit" style="display: none; margin-top: 15px;">
                                    <form id="address-form" action="{{ route('shipping-address.update') }}"
                                        method="POST"
                                        style=" padding: 20px;  border: 1px solid #ddd; border-radius: 8px;">

                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="order_id" value="{{ $orderAddress->id }}">

                                        <!-- Address Fields -->
                                        <label for="address"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Address:</label>
                                        <input type="text" id="address" name="address"
                                            value="{{ $orderAddress->address }}" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="city"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">City:</label>
                                        <input type="text" id="city" name="city"
                                            value="{{ $orderAddress->city }}" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="country"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Country:</label>
                                        <input type="text" id="country" name="country"
                                            value="{{ $orderAddress->country }}" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="postcode"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Postcode:</label>
                                        <input type="text" id="postcode" name="postcode"
                                            value="{{ $orderAddress->postcode }}" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="phone"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Phone:</label>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ $orderAddress->phone }}" required
                                            style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px;">

                                        <!-- Buttons -->
                                        <div style="display: flex; gap: 10px;">
                                            <button type="submit" class="btn btn-primary"
                                                style="flex: 1;  cursor: pointer;">
                                                Save
                                            </button>
                                            <button type="button" id="cancel-edit"
                                                style="flex: 1; padding: 10px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                                Cancel
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            @else
                                <!-- No Address: Show Add Address Form -->
                                <div id="address-add">
                                    <p
                                        style="background-color: #ffeaea; border: 1px solid #ffb3b3; padding: 15px; border-radius: 5px;  line-height: 1.6; margin-bottom: 20px;">
                                        No address found. Please add your shipping address below.
                                    </p>

                                    <form id="address-form" action="{{ route('shipping-address.store') }}"
                                        method="POST"
                                        style=" padding: 20px;  border: 1px solid #ddd; border-radius: 8px;">

                                        @csrf

                                        <label for="address"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Address:</label>
                                        <input type="text" id="address" name="address" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="city"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">City:</label>
                                        <input type="text" id="city" name="city" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="country"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Country:</label>
                                        <input type="text" id="country" name="country" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="postcode"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Postcode:</label>
                                        <input type="text" id="postcode" name="postcode" required
                                            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

                                        <label for="phone"
                                            style="display: block; margin-bottom: 5px; font-weight: bold;">Phone:</label>
                                        <input type="text" id="phone" name="phone" required
                                            style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px;">

                                        <button type="submit" class="btn btn-primary"
                                            style="width: 100%;  cursor: pointer;">
                                            Save
                                        </button>

                                    </form>

                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="tab__content" content id="change-password">
                        <h3 class="tab__header">Change Password</h3>
                        <div class="tab__body">
                            <form class="form grid" action="{{ route('user.change-password') }}" method="POST">
                                @csrf
                                <input type="password" name="new_password" placeholder="New Password"
                                    class="form__input" required />
                                <input type="password" name="new_password_confirmation" placeholder="Confirm Password"
                                    class="form__input" required />

                                <div class="form__btn">
                                    <button type="submit" class="btn btn--md">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
    @push('scripts')
        <script>
            // Show/Hide edit form
            document.addEventListener('DOMContentLoaded', function() {
                const editButton = document.getElementById('edit-button');
                const cancelEditButton = document.getElementById('cancel-edit');
                const addressView = document.getElementById('address-view');
                const addressEdit = document.getElementById('address-edit');

                if (editButton) {
                    editButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        addressView.style.display = 'none';
                        addressEdit.style.display = 'block';
                    });
                }

                if (cancelEditButton) {
                    cancelEditButton.addEventListener('click', function() {
                        addressEdit.style.display = 'none';
                        addressView.style.display = 'block';
                    });
                }
            });
        </script>
        <script>
            function previewImage(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('profile-image-preview');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        </script>
    @endpush
