<!DOCTYPE html>
<html>
<head>
    <title>Payment Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Payment Received</h2>
        <p>Hi {{ $order->name }},</p>
        <p>We have received your payment for order <strong>{{ $order->order_number }}</strong>.</p>
        
        <div style="background: #ecfdf5; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #a7f3d0;">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Amount Paid:</strong> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Status:</strong> Paid</p>
        </div>

        <p>We will now process your order. You will be notified when it is shipped.</p>
        
        <p>You can track your order status here: <a href="{{ route('order.track', ['order' => $order->order_number]) }}">Track Order</a></p>
        
        <p>Thank you!</p>
    </div>
</body>
</html>
