<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
// Show the form to create a new deal
public function create()
{
    return view('admin.admin-deals.admin-deal-create');
}

public function show($id)
{
    $deal = Deal::findOrFail($id);
    return view('admin.admin-deals.admin-deals-view', compact('deal'));
}


// Store a newly created deal in the database
public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'deal_title' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'price' => 'required|numeric',
        'original_price' => 'required|numeric',
        'offer_message' => 'required|string',
        // Add other validation rules for your fields
    ]);

    // Create a new deal
    Deal::create([
        'deal_title' => $request->deal_title,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'price' => $request->price,
        'original_price' => $request->original_price,
        'offer_message' => $request->offer_message,
        'limited_quantities' => $request->has('limited_quantities'), // Handle checkbox
        'collection_type' => $request->collection_type,
    ]);

    // Redirect to the deals index page or another page with a success message
    return redirect()->route('admin.deals.index')->with('success', 'Deal created successfully');
}

// Show the list of all deals
public function index()
{
    $deals = Deal::all();
    return view('admin.admin-deals.admin-deals-list', compact('deals'));
}

// Show the form to edit an existing deal
public function edit($id)
{
    $deal = Deal::findOrFail($id);
    return view('admin.admin-deals.admin-deal-edit', compact('deal'));
}

// Update an existing deal in the database
public function update(Request $request, $id)
{
    $request->validate([
        'deal_title' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'price' => 'required|numeric',
        'original_price' => 'required|numeric',
        'offer_message' => 'required|string',
        // Add other validation rules for your fields
    ]);

    $deal = Deal::findOrFail($id);
    $deal->update([
        'deal_title' => $request->deal_title,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'price' => $request->price,
        'original_price' => $request->original_price,
        'offer_message' => $request->offer_message,
        'limited_quantities' => $request->has('limited_quantities'),
        'collection_type' => $request->collection_type,
    ]);

    return redirect()->route('admin.deals.index')->with('success', 'Deal updated successfully');
}

// Delete a deal from the database
public function destroy($id)
{
    $deal = Deal::findOrFail($id);
    $deal->delete();

    return redirect()->route('admin.deals.index')->with('success', 'Deal deleted successfully');
}

}
