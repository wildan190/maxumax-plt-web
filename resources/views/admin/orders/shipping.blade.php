@extends('layouts.app')

@section('page-title', 'Shipping for #' . $order->order_number)

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.orders.show', $order) }}"
            style="color: #6b7280; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;">
            ← Back to Order
        </a>
    </div>

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.25rem;">Check Shipping Rates</h2>

        <form action="{{ route('admin.orders.checkRates', $order) }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Sender Details -->
                <div>
                    <h3 style="font-size: 1rem; color: #4b5563; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Sender (Shop)</h3>
                    
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Postcode</label>
                        <input type="text" name="pick_code" value="{{ old('pick_code', '88000') }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">State</label>
                        <input type="text" name="pick_state" value="{{ old('pick_state', 'Sabah') }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Country</label>
                        <input type="text" name="pick_country" value="{{ old('pick_country', 'MY') }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                </div>

                <!-- Receiver Details -->
                <div>
                    <h3 style="font-size: 1rem; color: #4b5563; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Receiver (Customer)</h3>
                    
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Postcode</label>
                        @php
                            $postal = '';
                            if (preg_match('/Postal (\d+)/', $order->address ?? '', $matches)) {
                                $postal = $matches[1];
                            }
                        @endphp
                        <input type="text" name="send_code" value="{{ old('send_code', $postal) }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">State</label>
                        <input type="text" name="send_state" value="{{ old('send_state') }}" required placeholder="e.g. W.P. KUALA LUMPUR"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Country</label>
                        <input type="text" name="send_country" value="{{ old('send_country', 'MY') }}" required
                            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem;">
                 <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">Parcel Weight (kg)</label>
                 <input type="number" step="0.1" name="weight" value="{{ old('weight', 1) }}" required
                        style="width: 100px; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <div style="margin-top: 2rem; text-align: right;">
                <button type="submit" 
                    style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                    Check Rates
                </button>
            </div>
        </form>
    </div>

    @if(isset($rates) && count($rates) > 0)
        <div style="margin-top: 2rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">Available Rates</h3>
            
            <div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                @php $cheapest = $rates[0] ?? null; @endphp
                @if($cheapest)
                    <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 0.875rem; color: #374151;">
                            Auto Book Termurah: 
                            <strong>{{ $cheapest['courier_name'] }}</strong> — {{ $cheapest['service_name'] }} (RM {{ number_format($cheapest['price'], 2) }})
                        </div>
                        <form action="{{ route('admin.orders.bookShipping', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $cheapest['service_id'] }}">
                            <input type="hidden" name="courier_source" value="{{ $cheapest['source'] ?? (str_contains(strtolower($cheapest['courier_name'] ?? ''), 'delyva') ? 'delyva' : 'easyparcel') }}">
                            <input type="hidden" name="weight" value="{{ old('weight') }}">
                            @foreach(['pick_code', 'pick_state', 'pick_country', 'send_code', 'send_state', 'send_country'] as $field)
                                <input type="hidden" name="{{ $field }}" value="{{ old($field) }}">
                            @endforeach
                            <input type="hidden" name="pick_name" value="{{ config('app.name') }}">
                            <input type="hidden" name="pick_contact" value="{{ old('pick_contact', '0123456789') }}">
                            <input type="hidden" name="pick_addr1" value="Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens">
                            <input type="hidden" name="send_name" value="{{ $order->name }}">
                            <input type="hidden" name="send_contact" value="{{ $order->phone }}">
                            <input type="hidden" name="send_addr1" value="{{ $order->address }}">
                            <button type="submit" 
                                style="background: #111827; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;"
                                onclick="return confirm('Auto book dengan tarif termurah?');">
                                Auto Book Termurah
                            </button>
                        </form>
                    </div>
                @endif
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <tr>
                            <th style="text-align: left; padding: 1rem; font-size: 0.875rem; color: #6b7280;">Courier</th>
                            <th style="text-align: left; padding: 1rem; font-size: 0.875rem; color: #6b7280;">Service</th>
                            <th style="text-align: right; padding: 1rem; font-size: 0.875rem; color: #6b7280;">Price (RM)</th>
                            <th style="padding: 1rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rates as $rate)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 1rem;">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        @if(isset($rate['courier_logo']))
                                            <img src="{{ $rate['courier_logo'] }}" alt="Logo" style="height: 30px; object-fit: contain;">
                                        @endif
                                        <span style="font-weight: 500;">{{ $rate['courier_name'] }}</span>
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    {{ $rate['service_name'] }} <br>
                                    <span style="font-size: 0.8rem; color: #6b7280;">{{ $rate['delivery'] }}</span>
                                </td>
                                <td style="padding: 1rem; text-align: right; font-weight: 600;">
                                    {{ $rate['price'] }}
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <form action="{{ route('admin.orders.bookShipping', $order) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="service_id" value="{{ $rate['service_id'] }}">
                                        <input type="hidden" name="courier_source" value="{{ $rate['source'] ?? (str_contains(strtolower($rate['courier_name'] ?? ''), 'delyva') ? 'delyva' : 'easyparcel') }}">
                                        <input type="hidden" name="weight" value="{{ old('weight') }}">
                                        
                                        @foreach(['pick_code', 'pick_state', 'pick_country', 'send_code', 'send_state', 'send_country'] as $field)
                                            <input type="hidden" name="{{ $field }}" value="{{ old($field) }}">
                                        @endforeach
                                        
                                        <input type="hidden" name="pick_name" value="{{ config('app.name') }}">
                                        <input type="hidden" name="pick_contact" value="{{ old('pick_contact', '0123456789') }}">
                                        <input type="hidden" name="pick_addr1" value="Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens">
                                        
                                        <input type="hidden" name="send_name" value="{{ $order->name }}">
                                        <input type="hidden" name="send_contact" value="{{ $order->phone }}">
                                        <input type="hidden" name="send_addr1" value="{{ $order->address }}">

                                        <button type="submit" 
                                            style="background: #059669; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;"
                                            onclick="return confirm('Confirm booking with this courier? This will place an order.');">
                                            Book Shipment
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
