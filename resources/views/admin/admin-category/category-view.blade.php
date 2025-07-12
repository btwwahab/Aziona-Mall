@extends('admin.admin-layout.master')

@section('title', 'View Category')
@section('content')
    <div class="page-content">
        <div class="container-xxl">

            <div class="row">
                <div class="col-xl-12 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="bg-light text-center rounded bg-light">
                                <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('admin-assets/images/product/p-1.png') }}"
                                    alt="Category Image" class="avatar-xxl">
                            </div>
                            <div class="mt-3">
                                <h4>Category Details</h4>
                                <div class="row">
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">No:</p>
                                        <h2 class="mb-0">{{ $category->no }}</h2>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Name</p>
                                        <h2 class="mb-0">{{ $category->name }}</h2>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <p class="mb-1 mt-2">Status</p>
                                        <h2 class="mb-0">
                                            @if ($category->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </h5>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Back to List</a>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="btn btn-warning">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
