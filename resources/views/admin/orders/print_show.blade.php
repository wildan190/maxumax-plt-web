<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Order #{{ $order->order_number }}</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; background: #fff; }
        .page { max-width: 900px; margin: 0 auto; padding: 1.5rem; }
        .header { margin-bottom: 1rem; }
        .title { margin:0; font-size: 1.25rem; font-weight: 700; color: #111827; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .label { color:#6b7280; font-size:0.8rem; }
        .value { font-weight:700; color:#111827; }
        .address { margin-top: 0.75rem; }
        @media print {
            .print-btn { display: none !important; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h2 class="title">Order #{{ $order->order_number }}</h2>
        </div>
        <div class="grid" style="margin-bottom: 1rem;">
            <div>
                <div class="label">Customer Name</div>
                <div class="value">{{ $order->name }}</div>
            </div>
            <div>
                <div class="label">Email</div>
                <div class="value" style="font-weight:400;">{{ $order->email ?? '—' }}</div>
            </div>
            <div>
                <div class="label">Phone</div>
                <div class="value" style="font-weight:400;">{{ $order->phone ?? '—' }}</div>
            </div>
            <div>
                <div class="label">Order Number</div>
                <div class="value" style="font-family:monospace; font-weight:700;">{{ $order->order_number }}</div>
            </div>
        </div>
        @if($order->address)
            <div class="address">
                <div class="label">Delivery Address</div>
                <div class="value" style="font-weight:400;">{{ $order->address }}</div>
            </div>
        @endif
        <div style="margin-top:1.5rem;">
            <button class="print-btn" onclick="window.print()" style="padding:0.5rem 0.75rem; border:none; border-radius:0.5rem; font-weight:600; background:#111827; color:#fff; cursor:pointer;">Print</button>
        </div>
    </div>
</body>
</html>
