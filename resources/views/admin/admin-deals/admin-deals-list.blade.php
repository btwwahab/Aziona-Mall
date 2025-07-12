@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <h2>All Deals</h2>

                    <div style="    display: flex; justify-content: end;">
                    <a href="{{ route('admin.deals.create') }}" class="btn btn-primary">Create New Deal</a>
                    </div>
                    <!-- Display deals table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Deal Name</th>
                                <th>Price</th>
                                <th>DiscountPrice</th>
                                <th>Offer Ends On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deals as $deal)
                                <tr>
                                    <td>{{ $deal->id }}</td>
                                    <td>{{ $deal->deal_title }}</td>
                                    <td>${{ $deal->price }}</td>
                                    <td>${{ $deal->original_price }}</td>
                                    <td>{{ \Carbon\Carbon::parse($deal->end_date)->format('d M, Y') }}</td>
                                    <td>
                                        <!-- Actions like Edit and Delete -->
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.deals.show', $deal->id) }}" class="btn btn-light btn-sm">
                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                        
                                            <a href="{{ route('admin.deals.edit', $deal->id) }}" class="btn btn-soft-primary btn-sm">
                                                <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                            </a>
                                        
                                            <form action="{{ route('admin.deals.destroy', $deal->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm delete-btn">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </button>
                                            </form>
                                        </div>
                                        
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
