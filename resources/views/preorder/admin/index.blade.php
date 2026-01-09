@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Preorders</h1>

        <p>
            <a href="{{ route('admin.preorders.export') }}" class="btn">Export CSV</a>
        </p>

        @if(session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Name</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Total (BND)</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preorders as $p)
                    <tr>
                        <td>{{ $p->order_number }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->jersey_type }} {{ $p->size ? '('.$p->size.')' : '' }}</td>
                        <td>{{ $p->quantity }}</td>
                        <td>{{ number_format($p->total_amount,2) }}</td>
                        <td>{{ $p->status }}</td>
                        <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($p->status !== 'paid')
                                <form method="POST" action="{{ route('admin.preorders.markPaid', $p) }}" style="display:inline">
                                    @csrf
                                    <button class="btn small">Mark Paid</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $preorders->links() }}
    </div>
@endsection
