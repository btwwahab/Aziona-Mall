@extends('admin.admin-layout.master')

@section('title', 'Order Confirmation')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <h2 class="mb-4">Latest Orders</h2>

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
                    <div class="card">
                        <div class="table-responsive table-centered">
                            <table class="table mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th class="ps-3">Order ID</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Customer Name</th>
                                        <th>Email ID</th>
                                        <th>Phone No.</th>
                                        <th>Address</th>
                                        <th>Payment Type</th>
                                        <th>Payment SS</th>
                                        <th>Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="ps-3">
                                                <a
                                                    href="{{ route('admin.payment.confirm', $order->id) }}">#{{ $order->id }}</a>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if ($order->items->isNotEmpty())
                                                    @php
                                                        $firstItem = $order->items->first();
                                                        $productImage =
                                                            $firstItem->product->image_front ?? 'default-image.jpg';
                                                    @endphp
                                                    <img src="{{ asset('storage/' . $productImage) }}" alt="product-image"
                                                        class="img-fluid avatar-sm">
                                                @else
                                                    <span>No product image</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="javascript:void(0)">{{ $order->name }}</a>
                                            </td>
                                            <td>{{ $order->email }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>{{ $order->address }}</td>
                                            <td>{{ ucfirst($order->payment_method) }}</td>
                                            <td>
                                                @if ($order->payment_screenshot)
                                                    <img src="{{ asset('storage/' . $order->payment_screenshot) }}"
                                                        alt="Payment Screenshot" style="max-width: 50px; max-height: 50px;">
                                                @else
                                                    <span>No Screenshot</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($order->status == 'awaiting_bank_transfer')
                                                    <span
                                                        class="badge bg-warning text-dark d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i> Verify
                                                        Payment
                                                    </span>
                                                @elseif ($order->status == 'confirmed')
                                                    <span
                                                        class="badge bg-success text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i> Payment
                                                        Confirmed
                                                    </span>
                                                @elseif ($order->status == 'cash_on_delivery')
                                                    <span
                                                        class="badge bg-secondary text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i> Cash on
                                                        Delivery
                                                    </span>
                                                @elseif ($order->status == 'paid')
                                                    <span
                                                        class="badge bg-success text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i> Stripe Paid
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-danger text-white d-inline-flex align-items-center px-2 py-1 rounded-sm font-weight-normal"
                                                        style="width: 150px;">
                                                        <i class="bx bxs-circle me-1" style="font-size: 10px;"></i>
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.order.view', $order->id) }}"
                                                    class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                                        class="align-middle fs-18"></iconify-icon></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer border-top">
                            <div class="row g-3">
                                <div class="col-sm">
                                    <div class="text-muted">
                                        Showing
                                        <span class="fw-semibold">{{ $orders->firstItem() }}</span>
                                        to
                                        <span class="fw-semibold">{{ $orders->lastItem() }}</span>
                                        of
                                        <span class="fw-semibold">{{ $orders->total() }}</span>
                                        orders
                                    </div>
                                </div>

                                <div class="col-sm-auto">
                                    <ul class="pagination m-0">
                                        <!-- Previous Page Link -->
                                        @if ($orders->onFirstPage())
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link"><i class="bx bx-left-arrow-alt"></i></a>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a href="{{ $orders->previousPageUrl() }}" class="page-link"><i
                                                        class="bx bx-left-arrow-alt"></i></a>
                                            </li>
                                        @endif

                                        <!-- Page Number Links -->
                                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                            <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">
                                                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        <!-- Next Page Link -->
                                        @if ($orders->hasMorePages())
                                            <li class="page-item">
                                                <a href="{{ $orders->nextPageUrl() }}" class="page-link"><i
                                                        class="bx bx-right-arrow-alt"></i></a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <a href="#" class="page-link"><i
                                                        class="bx bx-right-arrow-alt"></i></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
