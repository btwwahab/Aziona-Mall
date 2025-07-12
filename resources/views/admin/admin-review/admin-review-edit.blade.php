@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <h1>Edit Review</h1>

                    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $review->name }}">
                        </div>
                
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $review->email }}">
                        </div>
                
                        <div class="mb-3">
                            <label>Rating</label>
                            <input type="number" name="rating" class="form-control" min="1" max="5" value="{{ $review->rating }}">
                        </div>
                
                        <div class="mb-3">
                            <label>Comment</label>
                            <textarea name="comment" class="form-control">{{ $review->comment }}</textarea>
                        </div>
                
                        <button type="submit" class="btn btn-primary">Update Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection