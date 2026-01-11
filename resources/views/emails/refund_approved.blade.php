<!DOCTYPE html>
<html>
<head>
    <title>Refund Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Refund Approved</h2>
        <p>Hi {{ $order->name }},</p>
        <p>Your refund request for order <strong>{{ $order->order_number }}</strong> has been approved and processed.</p>
        
        <div style="background: #eff6ff; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #bfdbfe;">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Refund Amount:</strong> {{ $order->currency }} {{ number_format($order->refund_amount, 2) }}</p>
            <p><strong>Refund ID:</strong> {{ $order->stripe_refund_id ?? '-' }}</p>
        </div>

        <p>The funds should appear in your account within 5-10 business days, depending on your bank.</p>
        
        <p>Thank you.</p>
    </div>
</body>
</html>
