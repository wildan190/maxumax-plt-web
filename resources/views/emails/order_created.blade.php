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

            @if(!empty($order->items) && is_array($order->items) && count($order->items) > 0)
                <div style="margin: 15px 0;">
                    <strong>Items:</strong>
                    <ul style="padding-left: 20px; margin-top: 5px;">
                        @foreach($order->items as $item)
                            <li>
                                {{ $order->product->name ?? 'Item' }}
                                ({{ $item['variant_name'] ?? '-' }})
                                x {{ $item['quantity'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p><strong>Product:</strong> {{ $order->product->name ?? 'Custom Order' }}</p>
                @if($order->size)
                    <p><strong>Size:</strong> {{ $order->size }}</p>
                @endif
            @endif

            <p><strong>Total Quantity:</strong> {{ $order->quantity }}</p>
            <p><strong>Total Amount:</strong> {{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <p>You can track your order status here: <a
                href="{{ route('order.track', ['order' => $order->order_number]) }}">Track Order</a></p>

        <p>Thank you for shopping with us!</p>
    </div>
</body>

</html>