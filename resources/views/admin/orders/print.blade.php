<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Orders</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; background: #fff; }
        .page { max-width: 1200px; margin: 0 auto; padding: 1.5rem; }
        .title { margin:0 0 1rem 0; font-size: 1.25rem; font-weight: 700; color: #111827; }
        table { width:100%; border-collapse: collapse; background:#fff; border:1px solid #e5e7eb; border-radius:0.75rem; }
        th, td { padding:0.75rem; border-bottom:1px solid #f3f4f6; text-align:left; }
        th { border-bottom:1px solid #e5e7eb; }
        .print-btn { margin-top: 1rem; padding:0.5rem 0.75rem; border:none; border-radius:0.5rem; font-weight:600; background:#111827; color:#fff; cursor:pointer; }
        @media print {
            .print-btn { display: none !important; }
            body { background: #fff; }
        }
    </style>
</head>
<body>
    <div class="page">
        <h2 class="title">Printable Orders List</h2>
        <table>
            <thead>
            <tr>
                <th>Order Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td style="font-family:monospace;">{{ $order->order_number }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->email ?? '—' }}</td>
                    <td>{{ $order->phone ?? '—' }}</td>
                    <td>{{ $order->address ?? '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="print-btn" onclick="window.print()">Print</button>
    </div>
</body>
</html>
