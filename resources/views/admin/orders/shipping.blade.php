@extends('layouts.app')

@section('page-title', 'Shipping for #' . $order->order_number)

@section('content')
    <div class="resp-container">
        <style>
            .resp-container {
                max-width: 1000px;
                margin: 0 auto;
                padding: 0 16px
            }

            .muted-link {
                color: #6b7280;
                text-decoration: none;
                font-size: .95rem;
                display: inline-flex;
                align-items: center;
                gap: .5rem
            }

            .alert {
                padding: 12px;
                border-radius: 8px;
                margin-bottom: 12px
            }

            .alert-error {
                background: #fee2e2;
                color: #991b1b
            }

            .alert-success {
                background: #ecfdf5;
                color: #065f46
            }

            .card {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
                padding: 16px
            }

            .card h2 {
                margin: 0 0 16px 0;
                font-size: 1.25rem
            }

            .grid-2 {
                display: grid;
                grid-template-columns: 1fr;
                gap: 16px
            }

            @media(min-width:768px) {
                .grid-2 {
                    grid-template-columns: 1fr 1fr
                }
            }

            .label {
                display: block;
                font-size: .875rem;
                font-weight: 500;
                margin-bottom: 6px;
                color: #374151
            }

            .input {
                width: 100%;
                padding: 12px;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                font-size: .95rem
            }

            .actions {
                margin-top: 16px;
                text-align: right
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: #2563eb;
                color: #fff;
                padding: 12px 20px;
                border: none;
                border-radius: 10px;
                font-weight: 600;
                cursor: pointer
            }

            @media(max-width:767px) {
                .actions .btn {
                    width: 100%
                }
            }

            .section-title {
                font-size: 1.25rem;
                margin: 16px 0
            }

            .table-wrap {
                background: #fff;
                border-radius: 12px;
                overflow-x: auto;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .08)
            }

            .table {
                width: 100%;
                border-collapse: collapse;
                min-width: 720px
            }

            .table thead {
                background: #f9fafb;
                border-bottom: 1px solid #e5e7eb
            }

            .table th {
                padding: 14px;
                text-align: left;
                font-size: .875rem;
                color: #6b7280;
                white-space: nowrap
            }

            .table td {
                padding: 14px;
                border-bottom: 1px solid #f3f4f6
            }

            .rate-header {
                padding: 14px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                gap: 12px;
                align-items: center
            }

            .rate-info {
                font-size: .875rem;
                color: #374151
            }

            .btn-dark {
                background: #111827
            }

            .btn-success {
                background: #059669
            }

            .logo {
                height: 28px;
                object-fit: contain
            }

            .btn-sm {
                padding: 10px 14px;
                border-radius: 8px;
                font-size: .875rem
            }
        </style>
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('admin.orders.show', $order) }}" class="muted-link">← Back to Order</a>
        </div>



        <div class="card">
            <h2>Check Shipping Rates</h2>

            <form action="{{ route('admin.orders.checkRates', $order) }}" method="POST">
                @csrf

                <div class="grid-2">
                    <!-- Sender Details -->
                    <div>
                        <h3 class="label" style="margin:0;border-bottom:1px solid #e5e7eb;padding-bottom:8px;color:#4b5563">
                            Sender (Shop)</h3>

                        <div style="margin-top:12px">
                            <label class="label">Postcode</label>
                            <input class="input" type="text" name="pick_code" value="{{ old('pick_code', '88000') }}"
                                required>
                        </div>
                        <div style="margin-top:12px">
                            <label class="label">State</label>
                            <input class="input" type="text" name="pick_state" value="{{ old('pick_state', 'Sabah') }}"
                                required>
                        </div>
                        <div style="margin-top:12px">
                            <label class="label">Country</label>
                            <input class="input" type="text" name="pick_country" value="{{ old('pick_country', 'MY') }}"
                                required>
                        </div>
                    </div>

                    <!-- Receiver Details -->
                    <div>
                        <h3 class="label" style="margin:0;border-bottom:1px solid #e5e7eb;padding-bottom:8px;color:#4b5563">
                            Receiver (Customer)</h3>

                        <div style="margin-top:12px">
                            <label class="label">Postcode</label>
                            @php
                                $postal = '';
                                if (preg_match('/Postal (\d+)/', $order->address ?? '', $matches)) {
                                    $postal = $matches[1];
                                }
                                $parts = array_map('trim', explode(',', $order->address ?? ''));
                                $count = count($parts);
                                $region = $count >= 1 ? ($parts[$count - 1] ?? '') : '';
                                $province = $count >= 3 ? ($parts[$count - 3] ?? '') : '';
                                $city = $count >= 4 ? ($parts[$count - 4] ?? '') : '';
                                $derivedState = $province ?: (preg_match('/^(88|89)\d{3}$/', $postal) ? 'Sabah' : (preg_match('/^(93|94|95|96|97|98)\d{3}$/', $postal) ? 'Sarawak' : ''));
                            @endphp
                            <input class="input" type="text" name="send_code" value="{{ old('send_code', $postal) }}"
                                required>
                        </div>
                        <div style="margin-top:12px">
                            <label class="label">State</label>
                            <input class="input" type="text" name="send_state"
                                value="{{ old('send_state', $derivedState) }}" required
                                placeholder="e.g. W.P. KUALA LUMPUR">
                        </div>
                        <div style="margin-top:12px">
                            <label class="label">Country</label>
                            <input class="input" type="text" name="send_country" value="{{ old('send_country', 'MY') }}"
                                required>
                        </div>
                        <div
                            style="margin-top:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px">
                            <div style="font-size:.75rem;color:#6b7280;margin-bottom:8px">KYC Customer</div>
                            <div style="display:grid;grid-template-columns:1fr;gap:8px;font-size:.95rem">
                                <div><strong>Name:</strong> {{ $order->name }}</div>
                                <div><strong>Email:</strong> {{ $order->email ?? '-' }}</div>
                                <div><strong>Phone:</strong> {{ $order->phone ?? '-' }}</div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                    <div><strong>Region:</strong> {{ $region ?: '-' }}</div>
                                    <div><strong>State:</strong> {{ $province ?: ($derivedState ?: '-') }}</div>
                                </div>
                                <div><strong>City:</strong> {{ $city ?: '-' }}</div>
                                <div><strong>Address:</strong> {{ $order->address }}</div>
                                <div><strong>Postal:</strong> {{ $postal ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top:16px">
                    <label class="label">Parcel Weight (kg)</label>
                    <input class="input" type="number" step="0.1" name="weight" value="{{ old('weight', 1) }}" required
                        style="max-width:140px">
                </div>

                <div class="actions" style="margin-top:16px">
                    <button type="submit" class="btn">Check Rates</button>
                </div>
            </form>
        </div>

        @if(isset($rates) && count($rates) > 0)
            <div style="margin-top:16px">
                <h3 class="section-title">Available Rates</h3>
                <div class="table-wrap">
                    @php $cheapest = $rates[0] ?? null; @endphp
                    @if($cheapest)
                        <div class="rate-header">
                            <div class="rate-info">
                                Auto Book Termurah:
                                <strong>{{ $cheapest['courier_name'] }}</strong> — {{ $cheapest['service_name'] }} (RM
                                {{ number_format($cheapest['price'], 2) }})
                            </div>
                            <form action="{{ route('admin.orders.bookShipping', $order) }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $cheapest['service_id'] }}">
                                <input type="hidden" name="courier_source"
                                    value="{{ $cheapest['source'] ?? (str_contains(strtolower($cheapest['courier_name'] ?? ''), 'delyva') ? 'delyva' : 'easyparcel') }}">
                                <input type="hidden" name="weight" value="{{ old('weight') }}">
                                @foreach(['pick_code', 'pick_state', 'pick_country', 'send_code', 'send_state', 'send_country'] as $field)
                                    <input type="hidden" name="{{ $field }}" value="{{ old($field) }}">
                                @endforeach
                                <input type="hidden" name="pick_name" value="{{ config('app.name') }}">
                                <input type="hidden" name="pick_contact" value="{{ old('pick_contact', '0123456789') }}">
                                <input type="hidden" name="pick_addr1"
                                    value="Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens">
                                <input type="hidden" name="send_name" value="{{ $order->name }}">
                                <input type="hidden" name="send_contact" value="{{ $order->phone }}">
                                <input type="hidden" name="send_addr1" value="{{ $order->address }}">
                                <button type="submit" class="btn btn-dark btn-sm"
                                    onclick="return confirm('Auto book dengan tarif termurah?');">
                                    Auto Book Termurah
                                </button>
                            </form>
                        </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Courier</th>
                                <th>Service</th>
                                <th style="text-align:right">Price (RM)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rates as $rate)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:12px">
                                            @if(isset($rate['courier_logo']))
                                                <img src="{{ $rate['courier_logo'] }}" alt="Logo" class="logo">
                                            @endif
                                            <span style="font-weight:500">{{ $rate['courier_name'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $rate['service_name'] }} <br>
                                        <span style="font-size:.8rem;color:#6b7280">{{ $rate['delivery'] }}</span>
                                    </td>
                                    <td style="text-align:right;font-weight:600">
                                        {{ number_format($rate['price'], 2) }}
                                    </td>
                                    <td style="text-align:right">
                                        <form action="{{ route('admin.orders.bookShipping', $order) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="service_id" value="{{ $rate['service_id'] }}">
                                            <input type="hidden" name="courier_source"
                                                value="{{ $rate['source'] ?? (str_contains(strtolower($rate['courier_name'] ?? ''), 'delyva') ? 'delyva' : 'easyparcel') }}">
                                            <input type="hidden" name="weight" value="{{ old('weight') }}">

                                            @foreach(['pick_code', 'pick_state', 'pick_country', 'send_code', 'send_state', 'send_country'] as $field)
                                                <input type="hidden" name="{{ $field }}" value="{{ old($field) }}">
                                            @endforeach

                                            <input type="hidden" name="pick_name" value="{{ config('app.name') }}">
                                            <input type="hidden" name="pick_contact"
                                                value="{{ old('pick_contact', '0123456789') }}">
                                            <input type="hidden" name="pick_addr1"
                                                value="Lot 1-35, 1st Floor, Suria Sabah Shopping Mall, 1, Jln Tun Fuad Stephens">

                                            <input type="hidden" name="send_name" value="{{ $order->name }}">
                                            <input type="hidden" name="send_contact" value="{{ $order->phone }}">
                                            <input type="hidden" name="send_addr1" value="{{ $order->address }}">

                                            <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Confirm booking dengan courier ini? Ini akan membuat order.');">
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