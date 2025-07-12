@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <div style="max-width: 700px; margin: 40px auto; padding: 30px; border-radius: 16px; background-color: #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.08); font-family: 'Segoe UI', sans-serif;">
                        <h2 style="font-size: 24px; margin-bottom: 25px; color: #222; text-align: center;">Deal Details</h2>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">Deal Title:</strong>
                            <span style="font-size: 16px; color: #333;">{{ $deal->deal_title }}</span>
                        </div>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">Start Date:</strong>
                            <span style="font-size: 16px; color: #333;">{{ $deal->start_date->format('F d, Y') }}</span>
                        </div>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">End Date:</strong>
                            <span style="font-size: 16px; color: #333;">{{ $deal->end_date->format('F d, Y') }}</span>
                        </div>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">Price:</strong>
                            <span style="font-size: 16px; color: #28a745;">${{ number_format($deal->price, 2) }}</span>
                        </div>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">Original Price:</strong>
                            <span style="font-size: 16px; color: #dc3545;">${{ number_format($deal->original_price, 2) }}</span>
                        </div>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">Offer Message:</strong>
                            <div style="font-size: 16px; color: #333; background: #f9f9f9; padding: 12px; border-radius: 10px;">
                                {{ $deal->offer_message }}
                            </div>
                        </div>
                    
                        <div style="margin-bottom: 18px;">
                            <strong style="display: block; color: #555;">Limited Quantities:</strong>
                            <span style="font-size: 16px; color: #333;">{{ $deal->limited_quantities ? 'Yes' : 'No' }}</span>
                        </div>
                    
                        <div style="margin-bottom: 30px;">
                            <strong style="display: block; color: #555;">Collection Type:</strong>
                            <span style="font-size: 16px; color: #333;">{{ ucfirst($deal->collection_type) }} Collection</span>
                        </div>
                    
                        <a href="{{ route('admin.deals.edit', $deal->id) }}"
                            class="btn btn-primary w-100">
                            Edit Deal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
