<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function showCheckout()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $paymentMethods = PaymentMethod::where('status', 1)->get();

        $bankDetails = [
            'bank_name' => 'XYZ Bank',
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
            'ifsc_code' => 'XYZ1234',
        ];

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $orderAddress = Address::where('user_id', Auth::id())->first();

        return view('frontend.checkout', compact('cartItems', 'paymentMethods', 'bankDetails' , 'orderAddress'));
    }

    public function createTempOrder(Request $request)
    {
        try {
            Log::info('Temp order request received', $request->all());

            $request->validate([
                'items' => 'required|array',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $tempOrderId = 'temp_' . strtoupper(Str::random(10));

            $order = Order::create([
                'user_id' => $user->id,
                'temp_order_id' => $tempOrderId,
                'status' => 'pending',
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'payment_method' => 'Stripe',
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $image = $product ? $product->image_front : null;

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'product_image' => $image,
                ]);

                if ($product) {
                    $product->increment('sales_count', $item['quantity']);
                    $product->updateTrendingScore();
                }
                
            }

            return response()->json([
                'temp_order_id' => $tempOrderId,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Temp order creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function process(Request $request)
    {
        $orderController = new OrderController();

        if ($request->expectsJson()) {
            try {
                return $orderController->createOrder($request);
            } catch (\Exception $e) {
                Log::error('Order processing error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        // For form submissions, we need to handle differently
        if ($request->isMethod('post')) {
            try {
                // Process the form with validation
                $this->$request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email',
                    'phone' => 'required|string|max:20',
                    'address' => 'required|string',
                    'city' => 'required|string',
                    'country' => 'required|string',
                    'postcode' => 'required|string|max:10',
                    'subtotal' => 'required|numeric',
                    'total' => 'required|numeric',
                    'payment_method' => 'required|string|exists:payment_methods,name',
                    'items' => 'required|array',
                    'items.*.product_id' => 'required|exists:products,id',
                    'items.*.quantity' => 'required|integer|min:1',
                    'items.*.price' => 'required|numeric|min:0',
                ]);

                if ($request->payment_method === 'Bank Transfer') {
                    $this->$request->validate([
                        'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);
                }

                $result = $orderController->createOrder($request);

                // If the user expects JSON but we're not redirecting
                if ($request->wantsJson() && !method_exists($result, 'getTargetUrl')) {
                    return response()->json(['success' => true, 'message' => 'Order placed successfully!']);
                }

                return $result;
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => $e->errors(),
                    ], 422);
                }

                throw $e;
            } catch (\Exception $e) {
                Log::error('Order processing error: ' . $e->getMessage());

                if ($request->wantsJson()) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }

                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        return $orderController->createOrder($request);
    }

    public function stripeCheckout(Request $request)
    {
        $request->validate([
            'temp_order_id' => 'required|string',
        ]);

        try {
            $order = Order::where('temp_order_id', $request->temp_order_id)->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $orderController = new OrderController();
            // Pass both parameters to the method
            $checkoutUrl = $orderController->initiateStripeCheckout($request, $order);

            return response()->json([
                'url' => $checkoutUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe checkout error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}