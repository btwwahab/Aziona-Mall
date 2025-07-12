@extends('frontend.layout.master')

@section('title', 'Order Details')

@section('content')
    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list flex container">
                <li><a href="{{ route('home') }}" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><a href="{{ route('account') }}" class="breadcrumb__link">Account</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Order #{{ $order->order_number }}</span></li>
            </ul>
        </section>

        <!--=============== ORDER VIEW ===============-->
        <section class="accounts section--lg">
            <div class="accounts__container container">
                <h3 class="tab__header">Order Details - #{{ $order->order_number }}</h3>

                <div class="tab__body">
                    <div class="order__summary flex justify-between align-center">
                        <!-- Left Side: Order Details -->
                        <div class="order__summary-left" style="flex: 1; padding-right: 20px;">
                            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                            <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
                        </div>
                    
                        <!-- Right Side: Product Image -->
                        <div class="order__summary-right" style="flex-shrink: 0; text-align: center;">
                            @if ($order->items->first()?->product?->image_front)
                                <div style="border: 1px solid #ddd; padding: 10px; border-radius: 8px; background-color: #f9f9f9;">
                                    <p><strong>Preview:</strong></p>
                                    <img src="{{ asset('storage/' . $order->items->first()->product->image_front) }}"
                                        alt="Product Preview"
                                        style="max-width: 150px; height: auto; object-fit: cover; border-radius: 8px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    

                    <div class="order__items mt-3">
                        <h4 class="tab__header">Items:</h4>
                        <table class="placed__order-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->product && $item->product->image_front)
                                                <img src="{{ asset('storage/' . $item->product->image_front) }}"
                                                    alt="{{ $item->product->name }}" style="width: 60px; height: auto; object-fit: cover;">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="order__address mt-3">
                        <h4 class="tab__header">Shipping Address:</h4>
                        <p>{{ $order->shipping_address }}</p>
                    </div>

                    <div class="form__btn mt-4">
                        <a href="{{ route('account') }}" class="btn btn--md text-center"> Back to Account</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
