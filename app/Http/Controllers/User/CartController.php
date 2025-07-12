<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        return view('frontend.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        if ($product->stock < 1) {
            return response()->json(['message' => 'This product is out of stock.'], 400);
        }

        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return response()->json(['message' => 'Not enough stock available.'], 400);
            }
            $cart->update(['quantity' => $newQuantity]);
        } else {
            if ($request->quantity > $product->stock) {
                return response()->json(['message' => 'Not enough stock available.'], 400);
            }

            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

            // Only change this return statement - add the missing fields
    $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
    
    return response()->json([
        'success' => true, 
        'message' => 'Product added to cart.',
        'cart_count' => $cartCount,
        'product_name' => $product->name
    ]);

    }

    public function removeFromCart($id)
    {
        Cart::where('user_id', auth()->id())->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Product removed from cart');
    }

    public function updateCart(Request $request)
    {
        foreach ($request->cartItems as $cartItem) {
            $cart = Cart::where('id', $cartItem['cart_id'])->where('user_id', auth()->id())->first();

            if (!$cart) {
                return response()->json(['message' => 'Cart item not found.'], 404);
            }

            $product = Product::find($cart->product_id);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }

            if ($cartItem['quantity'] > $product->stock) {
                return response()->json(['message' => 'Not enough stock available.'], 400);
            }

            $cart->update(['quantity' => $cartItem['quantity']]);
        }

        return response()->json(['message' => 'Cart updated successfully.'], 200);
    }

    public function getCartCount()
    {
        $cartCount = auth()->check() ? Cart::where('user_id', auth()->id())->count() : 0;
        return response()->json(['cartCount' => $cartCount]);
    }

}
