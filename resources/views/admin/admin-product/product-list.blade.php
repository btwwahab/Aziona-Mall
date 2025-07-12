@extends('admin.admin-layout.master')

@section('title', 'Product List')
@section('content')

    {{-- START Wrapper --}}

    {{-- Header --}}

    {{-- Right Sidebar --}}

    {{-- Sidebar Here --}}

    <!-- ==================================================== -->
    <!-- Start right Content here -->
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-1">
                            <h4 class="card-title flex-grow-1">All Product List</h4>

                            <a href="{{ route('admin.product.add') }}" class="btn btn-sm btn-primary">
                                Add Product
                            </a>

                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    This Month
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="#!" class="dropdown-item">Download</a>
                                    <!-- item-->
                                    <a href="#!" class="dropdown-item">Export</a>
                                    <!-- item-->
                                    <a href="#!" class="dropdown-item">Import</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover table-centered">
                                    <thead class="bg-light-subtle">
                                        <tr>
                                            <th>Product Details</th>
                                            <th>Images</th>
                                            <th>Category</th>
                                            <th>Price Info</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($product as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div>
                                                            <h5 class="text-dark fw-medium fs-15">{{ $item->name }}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div
                                                            class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                            <img src="{{ asset('storage/' . $item->image_front) }}"
                                                                alt="front" class="avatar-md">
                                                        </div>
                                                        @if ($item->image_back)
                                                            <div
                                                                class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                                <img src="{{ asset('storage/' . $item->image_back) }}"
                                                                    alt="back" class="avatar-md">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <h4> <span class="badge bg-info ">
                                                            {{ $item->category->name ?? 'No Category' }}
                                                        </span></h4>
                                                </td>
                                                <td>
                                                    <p class="mb-1">Original: ${{ number_format($item->price, 2) }}</p>
                                                    @if ($item->discount_price)
                                                        <p class="mb-0 text-success">
                                                            Discount: ${{ number_format($item->discount_price, 2) }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-muted">
                                                        <span class="text-dark fw-medium">{{ $item->stock }}</span> Items
                                                    </p>
                                                </td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <h4> <span class="badge bg-success">Active</span></h4>
                                                    @else
                                                        <h4><span class="badge bg-danger">Inactive</span></h4>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.products.show', $item->id) }}"
                                                            class="btn btn-light btn-sm">
                                                            <iconify-icon icon="solar:eye-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="{{ route('admin.products.edit', $item->id) }}"
                                                            class="btn btn-soft-primary btn-sm">
                                                            <iconify-icon icon="solar:pen-2-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <form action="{{ route('admin.products.destroy', $item->id) }}"
                                                            method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn btn-soft-danger btn-sm delete-btn">
                                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                    class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No products found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer border-top">
                            @if ($product->hasPages())
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        {{-- Previous Page --}}
                                        <li class="page-item {{ $product->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $product->previousPageUrl() ?? 'javascript:void(0);' }}">Previous</a>
                                        </li>
                        
                                        {{-- Page Numbers --}}
                                        @for ($i = 1; $i <= $product->lastPage(); $i++)
                                            <li class="page-item {{ $product->currentPage() == $i ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $product->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                        
                                        {{-- Next Page --}}
                                        <li class="page-item {{ !$product->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $product->nextPageUrl() ?? 'javascript:void(0);' }}">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- End Container Fluid -->
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let form = this.closest("form");
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        customClass: {
                            confirmButton: "btn btn-primary w-xs me-2 mt-2",
                            cancelButton: "btn btn-danger w-xs mt-2"
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your product has been deleted.",
                                icon: "success",
                                customClass: {
                                    confirmButton: "btn btn-primary w-xs mt-2"
                                },
                                buttonsStyling: false
                            }).then(() => {
                                form.submit();
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                title: "Cancelled",
                                text: "Your product is safe.",
                                icon: "error",
                                customClass: {
                                    confirmButton: "btn btn-primary mt-2"
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush
