<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PlaceorderRequest;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{
    // Place order for Bank Transfer or COD (with dedicated FormRequest validation)
    public function placeOrder(PlaceorderRequest $request)
    {
        // This method is called when the form is directly submitted
        $request->validated();
        return $this->createOrder($request);
    }

    // This method handles both direct submissions and API-based order creation
    public function createOrder(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'user') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        DB::beginTransaction();
        try {
            // Check if order already exists (for Stripe payments)
            $existingOrder = null;
            if ($request->filled('temp_order_id')) {
                $existingOrder = Order::where('temp_order_id', $request->temp_order_id)->first();
            }

            if ($existingOrder) {
                // Update the existing order with form details
                $existingOrder->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postcode' => $request->postcode,
                    'order_note' => $request->order_note,
                ]);

                $order = $existingOrder;
            } else {
                // Create a new order
                $order = Order::create([
                    'user_id' => $user->id,
                    'temp_order_id' => $request->temp_order_id ?? 'order_' . strtoupper(Str::random(10)),
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postcode' => $request->postcode,
                    'order_note' => $request->order_note,
                    'subtotal' => $request->subtotal,
                    'total' => $request->total,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                ]);

                // Create Order Items (only for new orders)
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    $productImage = $product ? $product->image_front : 'default.jpg';

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'product_image' => $productImage,
                    ]);

                    if ($product) {
                        $product->increment('sales_count', $item['quantity']);
                        $product->updateTrendingScore();
                    }

                }
            }

            // Handle Payment Processing
            $paymentResult = $this->processPayment($request, $order);

            // For Stripe, we'll return the redirect URL instead of completing the transaction here
            if ($request->payment_method === 'Stripe' && is_string($paymentResult)) {
                DB::commit();

                if ($request->expectsJson()) {
                    return response()->json(['url' => $paymentResult]);
                }

                return redirect()->away($paymentResult);
            }

            DB::commit();

            // Mail::to($order->email)->send(new OrderPlacedMail($order));

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('home')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function processPayment(Request $request, Order $order)
    {
        if ($order->payment_method === 'Stripe') {
            // For Stripe payment, we don't process the payment here but initiate Stripe checkout
            return $this->initiateStripeCheckout($request, $order);
        }

        // For Bank Transfer or COD
        return match ($order->payment_method) {
            'Bank Transfer' => $this->processBankTransferPayment($request, $order),
            'Cash on Delivery' => $this->processCashOnDeliveryPayment($order),
            default => true,
        };
    }

    private function processBankTransferPayment(Request $request, Order $order)
    {
        try {
            $order->status = 'awaiting_bank_transfer';

            if ($request->hasFile('payment_screenshot')) {
                $order->payment_screenshot = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
            }

            $order->save();

            Log::info('Bank transfer processed and order saved');
            return true;
        } catch (\Exception $e) {
            Log::error('Error in processBankTransferPayment: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processCashOnDeliveryPayment(Order $order)
    {
        $order->status = 'cash_on_delivery';
        $order->save();

        return true;
    }

    public function initiateStripeCheckout(Request $request, Order $order)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Prepare Stripe line items
        $lineItems = [];
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => (int) ($item->price * 100), // Convert to cents and ensure integer
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Create Stripe session
        try {
            $checkoutSession = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer_email' => $order->email ?? Auth::user()->email,
                'metadata' => [
                    'order_id' => $order->temp_order_id,  // This is correctly passing the temp_order_id
                ],
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
            ]);

            return $checkoutSession->url;
        } catch (\Exception $e) {
            Log::error('Stripe session creation error: ' . $e->getMessage());
            throw $e;
        }
    }
    // In OrderController, update the stripeSuccess method
    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::retrieve($sessionId);
            $tempOrderId = $session->metadata->order_id ?? null;

            if ($tempOrderId) {
                $order = Order::where('temp_order_id', $tempOrderId)->first();
                if ($order) {
                    $order->status = 'paid';
                    $order->payment_intent = $session->payment_intent;
                    $order->save();

                    // Send confirmation email
                    Mail::to($order->email)->send(new OrderPlacedMail($order));

                    // Clear cart
                    Cart::where('user_id', $order->user_id)->delete();

                    // Add a flash message for SweetAlert
                    session()->flash('success', 'Payment completed successfully! Your order has been confirmed.');

                    return view('home', compact('order'));
                }
            }

            session()->flash('error', 'Order not found for Stripe session.');
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Stripe success error: ' . $e->getMessage());
            session()->flash('error', 'Stripe payment verification failed.');
            return redirect()->route('home');
        }
    }

    // Update the stripeCancel method
    public function stripeCancel(Request $request)
    {
        session()->flash('error', 'Payment was cancelled. Please try again.');
        return redirect()->route('checkout');
    }
}