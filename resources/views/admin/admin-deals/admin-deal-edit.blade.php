@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <h2>Edit Deal: {{ $deal->deal_title }}</h2>

                    <!-- Check for validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div style="    display: flex; justify-content: end;">
                        <a href="{{ route('admin.deals.index') }}" class="btn btn-primary "
                            >Back to Deals</a>
                    </div>
                    <!-- Edit Deal Form -->
                    <form action="{{ route('admin.deals.update', $deal->id) }}" method="POST"
                        style=" margin: 40px auto; padding: 30px; border-radius: 16px; background-color: #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.08); font-family: 'Segoe UI', sans-serif;">
                        @csrf
                        @method('PUT')

                        <h2 style="font-size: 24px; margin-bottom: 25px; color: #222; text-align: center;">Edit Deal</h2>

                        <div style="margin-bottom: 20px;">
                            <label for="deal_title"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Deal
                                Title</label>
                            <input type="text" id="deal_title" name="deal_title"
                                value="{{ old('deal_title', $deal->deal_title) }}" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="start_date"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Start
                                Date</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ old('start_date', $deal->start_date->format('Y-m-d')) }}" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="end_date"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">End Date</label>
                            <input type="date" id="end_date" name="end_date"
                                value="{{ old('end_date', $deal->end_date->format('Y-m-d')) }}" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="price"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Price ($)</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $deal->price) }}"
                                required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="original_price"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Original Price
                                ($)</label>
                            <input type="number" id="original_price" name="original_price"
                                value="{{ old('original_price', $deal->original_price) }}" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="offer_message"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Offer
                                Message</label>
                            <textarea id="offer_message" name="offer_message" required
                                style="width: 100%; height: 100px; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">{{ old('offer_message', $deal->offer_message) }}</textarea>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: flex; align-items: center; font-weight: 600; color: #333;">
                                <input type="checkbox" id="limited_quantities" name="limited_quantities"
                                    {{ $deal->limited_quantities ? 'checked' : '' }}
                                    style="margin-right: 10px; transform: scale(1.2);">
                                Limited Quantities
                            </label>
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label for="collection_type"
                                style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Collection
                                Type</label>
                            <select id="collection_type" name="collection_type"
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; font-size: 15px;">
                                <option value="summer" {{ $deal->collection_type == 'summer' ? 'selected' : '' }}>Summer
                                    Collection</option>
                                <option value="winter" {{ $deal->collection_type == 'winter' ? 'selected' : '' }}>Winter
                                    Collection</option>
                                <!-- Add more options here if needed -->
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Update Deal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
