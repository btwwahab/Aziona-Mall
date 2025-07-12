@extends('admin.admin-layout.master')

@section('title', 'Order Confirmation')

@section('content')
    <div class="page-content py-4">
        <div class="container-xxl">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Order Details -->
            <div class="order-details p-4 bg-white rounded shadow-sm">
                <div class="row mb-4">
                    <!-- Left Column Heading -->
                    <div class="col-md-6 mb-2">
                        <h4 class="section-title text-success border-bottom pb-2">Customer & Order Details</h4>
                    </div>
                
                    <!-- Right Column Heading -->
                    <div class="col-md-6 mb-2">
                        <h4 class="section-title text-success border-bottom pb-2">Ordered Product</h4>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Customer Info -->
                    <div class="col-md-6">
                        <p><strong>Customer Name:</strong> {{ $order->name }}</p>
                        <p><strong>Email:</strong> {{ $order->email }}</p>
                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p><strong>Address:</strong> {{ $order->address }}</p>
                        <p><strong>City:</strong> {{ $order->city }}</p>
                        <p><strong>Country:</strong> {{ $order->country }}</p>
                        <p><strong>Postcode:</strong> {{ $order->postcode }}</p>
                        <p><strong>Order Note:</strong> {{ $order->order_note ?? 'N/A' }}</p>
                    </div>
                
                    <div class="col-md-6">
                        <div class="row g-4">
                            @foreach ($order->items as $item)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm p-3" style="border-radius: 12px; background-color: #fff;">
                                        <img src="{{ asset('storage/' . $item->product->image_front) }}"
                                             alt="{{ $item->product->name }}"
                                             class="img-fluid rounded mb-3"
                                             style="height: 200px; object-fit: cover; border-radius: 10px;">
                    
                                        <div class="text-center">
                                            <h5 class="fw-bold mb-2" style="font-size: 18px;">
                                                {{ $item->product->name }}
                                            </h5>
                                            <p class="mb-1" style="font-size: 16px;"><strong>Qty:</strong> {{ $item->quantity }}</p>
                                            <p class="mb-0" style="font-size: 16px;"><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
    
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary mt-4">
                    <h4 class="section-title border-bottom pb-2 mb-3 text-primary">Summary</h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Subtotal:</strong></span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Total:</strong></span>
                            <span class="text-success fw-bold">${{ number_format($order->total, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Status:</strong></span>
                            @php
                            $status = strtolower($order->status);
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'completed' => 'primary',
                                'rejected' => 'danger',
                                'processing' => 'info',
                                'cash_on_delivery' => 'secondary', 
                                'awaiting_bank_transfer' => 'primary', 
                            ];
                        
                            // Handle specific status text and badge color
                            $badgeColor = $statusColors[$status] ?? 'secondary';
                        
                            if ($status == 'awaiting_bank_transfer') {
                                $statusText = 'Verify Payment';
                            } elseif ($status == 'cash_on_delivery') {
                                $statusText = 'Cash on Delivery';
                            } else {
                                $statusText = ucfirst($order->status);
                            }
                        @endphp
                        
                        <span class="badge bg-{{ $badgeColor }} text-uppercase px-3 py-2">
                            {{ $statusText }}
                        </span>
                        

                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Payment Method:</strong></span>
                            <span>{{ $order->payment_method }}</span>
                        </li>
                    </ul>

                    @if ($order->payment_method == 'Bank Transfer')
                        @if ($order->payment_screenshot)
                            <div class="payment-screenshot mt-4">
                                <label class="fw-bold mb-2 d-block">Payment Screenshot:</label>
                                <div class="border rounded p-2 bg-light d-inline-block shadow-sm">
                                    <a href="{{ asset('storage/' . $order->payment_screenshot) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $order->payment_screenshot) }}"
                                            alt="Payment Screenshot" class="img-fluid rounded"
                                            style="max-width: 100%; max-height: 400px; object-fit: contain;">
                                    </a>
                                </div>
                            </div>
                        @endif


                        <div class="payment-actions mt-4 d-flex gap-2">
                            <form action="{{ route('admin.payment.approve', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>

                            <form action="{{ route('admin.payment.reject', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </form>
                        </div>
                    @elseif ($order->payment_method == 'Cash on Delivery')
                        <p class="mt-3">
                            <strong>Payment Status:</strong>
                            <span class="badge bg-warning text-dark">Pending (Cash on Delivery)</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
