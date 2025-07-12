<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postcode' => $request->postcode,
            'phone' => $request->phone,
        ]);

        return redirect()->route('account')->with('success', 'Shipping address added successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
        ]);

        $address = Address::find($request->order_id);

        if (!$address) {
            return redirect()->back()->with('error', 'Address not found.');
        }

        $address->update([
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postcode' => $request->postcode,
            'phone' => $request->phone,
        ]);

        return redirect()->route('account')->with('success', 'Shipping address updated successfully!');
    }
}
