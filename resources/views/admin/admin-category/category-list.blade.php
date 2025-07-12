@extends('admin.admin-layout.master')

@section('title', 'Category List')
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
        <div class="container-xxl">

            <div class="row">
                {{-- @foreach ($categories->random(4) as $category)
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <div
                                    class="rounded bg-{{ collect(['primary', 'secondary', 'warning', 'info'])->random() }}-subtle d-flex align-items-center justify-content-center mx-auto">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="avatar-xl">
                                </div>
                                <h4 class="mt-3 mb-0">{{ $category->name }}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach --}}
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center gap-1">
                            <h4 class="card-title flex-grow-1">All Categories List</h4>

                            <a href="{{ route('admin.category.add') }}" class="btn btn-sm btn-primary">
                                Add Category
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
                                            <th style="width: 20px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                                    <label class="form-check-label" for="selectAll"></label>
                                                </div>
                                            </th>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($categories as $category)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input category-checkbox"
                                                            id="check-{{ $category->id }}">
                                                        <label class="form-check-label"
                                                            for="check-{{ $category->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $category->no }}
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center ">
                                                        <p class=" fw-medium fs-15 mb-0">
                                                            {{ $category->name }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        <img src="{{ asset('storage/' . $category->image) }}" alt=""
                                                            class="avatar-md">
                                                    </div>
                                                </td>
                                                <td>
                                                   <h4> <span
                                                        class="badge {{ $category->status ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                                    </span></h4>             
                                                </td>
                                                <td>{{ $category->created_at->format('F-d-Y') }}</td>
                                                <td>{{ $category->created_at->format('h:i A') }}</td>

                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('admin.categories.show', $category->id) }}"
                                                            class="btn btn-light btn-sm">
                                                            <iconify-icon icon="solar:eye-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </a>

                                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                            class="btn btn-soft-primary btn-sm"><iconify-icon
                                                                icon="solar:pen-2-broken"
                                                                class="align-middle fs-18"></iconify-icon></a>

                                                        <form
                                                            action="{{ route('admin.categories.destroy', $category->id) }}"
                                                            method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-light btn-sm delete-btn">
                                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                    class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No categories found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                        <div class="card-footer border-top">
                            @if ($categories->hasPages())
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end mb-0">
                                    {{-- Previous Page --}}
                                    <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $categories->previousPageUrl() ?? 'javascript:void(0);' }}">Previous</a>
                                    </li>
                    
                                    {{-- Page Numbers --}}
                                    @for ($i = 1; $i <= $categories->lastPage(); $i++)
                                        <li class="page-item {{ $categories->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                    
                                    {{-- Next Page --}}
                                    <li class="page-item {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $categories->nextPageUrl() ?? 'javascript:void(0);' }}">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Container Fluid -->
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Select/Deselect all checkboxes
                $("#selectAll").click(function() {
                    $(".category-checkbox").prop("checked", this.checked);
                });

                $(".category-checkbox").click(function() {
                    if ($(".category-checkbox:checked").length == $(".category-checkbox").length) {
                        $("#selectAll").prop("checked", true);
                    } else {
                        $("#selectAll").prop("checked", false);
                    }
                });
            });

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
                                    text: "Your category has been deleted.",
                                    icon: "success",
                                    customClass: {
                                        confirmButton: "btn btn-primary w-xs mt-2"
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    form
                                        .submit();
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    title: "Cancelled",
                                    text: "Your category is safe.",
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
