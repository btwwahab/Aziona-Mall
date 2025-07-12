@extends('admin.admin-layout.master')

@section('title', 'View Product')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="bg-light text-center rounded bg-light mb-3">
                                        <h5 class="mb-2">Front Image</h5>
                                        <img src="{{ $product->image_front ? asset('storage/' . $product->image_front) : asset('admin-assets/images/product/p-1.png') }}"
                                            alt="Product Front Image" class="avatar-xxl">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light text-center rounded bg-light">
                                        <h5 class="mb-2">Back Image</h5>
                                        <img src="{{ $product->image_back ? asset('storage/' . $product->image_back) : asset('admin-assets/images/product/p-1.png') }}"
                                            alt="Product Back Image" class="avatar-xxl">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h4>Product Details</h4>
                                <div class="row">
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Name:</p>
                                        <h5 class="mb-0">{{ $product->name }}</h5>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Category:</p>
                                        <h5 class="mb-0">{{ $product->category->name }}</h5>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Status:</p>
                                        <h5 class="mb-0">
                                            @if ($product->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Price:</p>
                                        <h5 class="mb-0">${{ number_format($product->price, 2) }}</h5>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Discount Price:</p>
                                        <h5 class="mb-0">${{ number_format($product->discount_price, 2) }}</h5>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Stock:</p>
                                        <h5 class="mb-0">{{ $product->stock }}</h5>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <p class="mb-1">Description:</p>
                                        <p class="text-muted">{{ $product->description }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <p class="mb-1">Tags:</p>
                                        <p class="text-muted">{{ $product->tags ?? 'No tags' }}</p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back to List</a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection