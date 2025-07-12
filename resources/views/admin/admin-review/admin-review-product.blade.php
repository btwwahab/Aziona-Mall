@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <h1>Products With Reviews</h1>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Total Reviews</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->reviews_count }}</td>
                                    <td>
                                        <a href="{{ route('admin.reviews.product-reviews', $product->id) }}" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken"
                                            class="align-middle fs-18"></iconify-icon></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection