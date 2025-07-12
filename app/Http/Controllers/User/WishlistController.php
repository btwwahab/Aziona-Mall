<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        // Remove this line as it's causing the error
        // $this->middleware('auth');
    }

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist');
        }

        try {
            $wishlistItems = Wishlist::with('product')
                ->where('user_id', auth()->id())
                ->get();

            return view('frontend.wishlist', compact('wishlistItems'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to fetch wishlist items');
        }
    }

    public function addToWishlist(Request $request)
{
    if (!auth()->check()) {
        return response()->json(['status' => 'error', 'message' => 'Please login to add items to wishlist'], 401);
    }

    try {
        Wishlist::updateOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id
        ]);

        return response()->json(['status' => 'success', 'message' => 'Product added to wishlist']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Unable to add product to wishlist'], 500);
    }
}

    public function removeFromWishlist($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to remove items from wishlist');
        }

        try {
            Wishlist::where('user_id', auth()->id())
                   ->where('id', $id)
                   ->delete();

            return redirect()->back()->with('success', 'Product removed from wishlist');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to remove product from wishlist');
        }
    }

    public function wishlistCount()
{
    $wishlistCount = auth()->check() ? Wishlist::where('user_id', auth()->id())->count() : 0;
    return response()->json(['wishlistCount' => $wishlistCount]);
}

}