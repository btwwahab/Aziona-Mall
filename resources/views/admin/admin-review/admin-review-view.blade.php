@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <h1>Reviews for: {{ $product->name }}</h1>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                   <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
                    <a href="{{ route('admin.reviews.products') }}" class="btn btn-primary ">← Back to Products</a>
                   </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Reviewer</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->reviews as $review)
                                <tr>
                                    <td>{{ $review->name }}</td>
                                    <td>{{ $review->rating }}⭐</td>
                                    <td>{{ Str::limit($review->comment, 60) }}</td>
                                    <td>{{ $review->created_at->setTimezone('Asia/Karachi')->format('F d, Y h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('admin.reviews.edit', $review->id) }}"
                                            class="btn btn-soft-primary btn-sm"><iconify-icon
                                            icon="solar:pen-2-broken"
                                            class="align-middle fs-18"></iconify-icon></a>
                                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                            style="display:inline-block;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-light btn-sm delete-btn"
                                                onclick="confirmDelete(this)"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                class="align-middle fs-18"></iconify-icon></button>
                                        </form>

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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to undo this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
@endpush
