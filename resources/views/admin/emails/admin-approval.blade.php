<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f7f7f7; color: #333; line-height: 1.6;">

    <div style="max-width: 650px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #6366F1 0%, #088179 100%); padding: 30px 40px; text-align: center; color: white; position: relative;">
            <div style="font-size: 24px; font-weight: 700; margin-bottom: 15px; letter-spacing: 1px;">Aziona Mall</div>
            <div style="background-color: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 30px; font-size: 14px; font-weight: 600; display: inline-block; margin-bottom: 15px;">
                {{ $statusType == 'approved' ? 'Order Approved' : 'Order Rejected' }}
            </div>
            <h1 style="margin: 0; font-size: 30px; font-weight: 700;">Order Status Update</h1>
            <div style="font-size: 16px; opacity: 0.9; margin-top: 5px;">Order #{{ $order->id }}</div>
        </div>

        <!-- Content -->
        <div style="padding: 40px;">
            <p style="font-size: 18px; margin-bottom: 20px;">Hi {{ $order->name }},</p>
            <p style="margin-bottom: 20px;">We've reviewed your order, and it has been {{ $statusType == 'approved' ? 'approved' : 'rejected' }}. Here's a summary of your order:</p>

            <!-- Order Details -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 20px; font-weight: 600; color: #088179; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #f0f0f0;">
                    Order Details</h2>
                <div style="background-color: #fafafa; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px;">
                        <div style="font-weight: 600; color: #555;">Order Date:</div>
                        <div style="text-align: right;">{{ date('F j, Y', strtotime($order->created_at)) }}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px;">
                        <div style="font-weight: 600; color: #555;">Order Number:</div>
                        <div style="text-align: right;">{{ $order->id }}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px;">
                        <div style="font-weight: 600; color: #555;">Payment Method:</div>
                        <div style="text-align: right;">{{ $order->payment_method }}</div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 20px; font-weight: 600; color: #088179; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #f0f0f0;">
                    Shipping Information</h2>
                <div style="background-color: #f7f9ff; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                    <div style="line-height: 1.7;">
                        <strong>{{ $order->name }}</strong><br>
                        {{ $order->address }}<br>
                        {{ $order->city }}, {{ $order->country }} {{ $order->postcode }}<br>
                        <strong>Email:</strong> {{ $order->email }}<br>
                        <strong>Phone:</strong> {{ $order->phone }}
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div style="background-color: #f7f9ff; border-radius: 8px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px;">
                    <div>Subtotal</div>
                    <div>${{ number_format($order->subtotal ?? $order->total * 0.9, 2) }}</div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px;">
                    <div>Shipping</div>
                    <div>${{ number_format($order->shipping_cost ?? $order->total * 0.1, 2) }}</div>
                </div>
                @if ($order->discount)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px;">
                        <div>Discount</div>
                        <div>-${{ number_format($order->discount, 2) }}</div>
                    </div>
                @endif
                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; color: #088179; border-top: 2px solid #e0e0e0; padding-top: 12px; margin-top: 12px;">
                    <div>Total</div>
                    <div>${{ number_format($order->total, 2) }}</div>
                </div>
            </div>

            <p>If you have any questions, feel free to reach out to us!</p>

            <p>Best regards,<br>The Aziona Mall Team</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f7f9ff; padding: 30px; text-align: center; font-size: 14px; color: #666; border-top: 1px solid #f0f0f0;">
            <div>&copy; {{ date('Y') }} Aziona Mall by Aziona. All rights reserved.</div>
        </div>
    </div>
</body>
</html>
