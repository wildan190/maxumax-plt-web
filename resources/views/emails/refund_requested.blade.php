<!DOCTYPE html>
<html>
<head>
    <title>Refund Request Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Refund Request Received</h2>
        <p>Hi {{ $order->name }},</p>
        <p>We have received your refund request for order <strong>{{ $order->order_number }}</strong>.</p>
        
        <div style="background: #fff7ed; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #fed7aa;">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Reason:</strong> {{ $order->refund_reason }}</p>
            <p><strong>Requested Amount:</strong> {{ $order->currency }} {{ number_format($order->refund_amount, 2) }}</p>
        </div>

        <p>Our team will review your request and get back to you shortly.</p>
        
        <p>Thank you.</p>
    </div>
</body>
</html>
