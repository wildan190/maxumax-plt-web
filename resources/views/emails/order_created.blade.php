<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2>Thank you for your order!</h2>
        <p>Hi {{ $order->name }},</p>
        <p>Your order has been placed successfully. Here are the details:</p>
        
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Product:</strong> {{ $order->product->name ?? 'Custom Order' }}</p>
            <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
            <p><strong>Total Amount:</strong> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <p>You can track your order status here: <a href="{{ route('order.track', ['order' => $order->order_number]) }}">Track Order</a></p>
        
        <p>Thank you for shopping with us!</p>
    </div>
</body>
</html>
