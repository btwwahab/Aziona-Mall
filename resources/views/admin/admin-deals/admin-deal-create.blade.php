@extends('admin.admin-layout.master')

@section('title', 'Add Deals')
@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-8 ">
                    <h1>Create New Deal</h1>
                    <form action="{{ route('admin.deals.store') }}" method="POST" style=" margin: 40px auto; padding: 30px; border-radius: 16px; background-color: #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.08); font-family: 'Segoe UI', sans-serif;">
                        @csrf
                        <h2 style="font-size: 24px; margin-bottom: 25px; color: #222; text-align: center;">Create New Deal</h2>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="deal_title" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Deal Title</label>
                            <input type="text" name="deal_title" id="deal_title" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="limited_quantities" style="display: flex; align-items: center; font-weight: 600; color: #333;">
                                <input type="checkbox" name="limited_quantities" id="limited_quantities" style="margin-right: 10px;">
                                Limited Quantities
                            </label>
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="collection_type" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Collection Type</label>
                            <input type="text" name="collection_type" id="collection_type" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="price" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Deal Price ($)</label>
                            <input type="number" step="0.01" name="price" id="price" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="original_price" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Original Price ($)</label>
                            <input type="number" step="0.01" name="original_price" id="original_price" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="offer_message" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Offer Message</label>
                            <input type="text" name="offer_message" id="offer_message" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px;">
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="start_date" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px; color: #555;">
                        </div>
                    
                        <div style="margin-bottom: 20px;">
                            <label for="end_date" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333;">End Date</label>
                            <input type="date" name="end_date" id="end_date" required
                                style="width: 100%; padding: 12px 15px; border: 1px solid #ccc; border-radius: 10px; background: #f9f9f9; font-size: 15px; color: #555;">
                        </div>
                    
                        <button type="submit" class="btn btn-primary w-100">
                            Create Deal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection