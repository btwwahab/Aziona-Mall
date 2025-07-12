<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminApprovalMail;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Mail;

class PaymentMethodController extends Controller
{
    // Show all payment methods in the admin panel
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.admin-order.admin-payment', compact('methods'));
    }

    // Update payment method status
    public function updateStatus(Request $request, $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->status = $request->status;
        $method->save();

        return redirect()->back()->with('success', 'Payment method updated successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->status = $request->status;
        $paymentMethod->save();

        return back()->with('success', 'Payment method updated successfully.');
    }


    public function orderConfirm()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.admin-order.admin-order-confirmation', compact('orders'));
    }

    public function approvePayment($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'confirmed'; // Or any relevant status like 'paid'
        $order->save();

        // Send confirmation email to the user
        Mail::to($order->user->email)->send(new AdminApprovalMail($order, 'approved'));

        return redirect()->route('admin.payment.confirm', $order->id)->with('success', 'Payment approved successfully.');
    }

    public function rejectPayment($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'rejected'; // Or any status you define for rejected payments
        $order->save();

        // Send rejection email to the user
        Mail::to($order->user->email)->send(new AdminApprovalMail($order, 'rejected'));

        return redirect()->route('admin.payment.confirm', $order->id)->with('error', 'Payment has been rejected.');
    }

    public function viewOrder($id)
    {
        // Retrieve the order by its ID along with related items (eager loading the relationship)
        $order = Order::with('items.product')->findOrFail($id);

        // Return the order view with the order details
        return view('admin.admin-order.admin-order-view', compact('order'));
    }

}
