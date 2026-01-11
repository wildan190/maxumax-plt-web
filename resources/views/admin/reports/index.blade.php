@extends('layouts.app')

@section('title', 'Sales Report')

@section('page-title', 'Sales Report')
@section('page-subtitle', 'Sales and transaction performance summary')

@section('content')
<div>
    <!-- Filter Section -->
    <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem;">
        <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label for="start_date" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" style="width: 100%; padding: 0.625rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; background: white;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label for="end_date" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" style="width: 100%; padding: 0.625rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; background: white;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label for="currency" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Currency</label>
                <select id="currency" name="currency" onchange="this.form.submit()" style="width: 100%; padding: 0.625rem; border: 1px solid var(--border-gray); border-radius: 0.5rem; background: white;">
                    <option value="MYR" {{ $currentCurrency == 'MYR' ? 'selected' : '' }}>MYR (RM)</option>
                    <option value="BND" {{ $currentCurrency == 'BND' ? 'selected' : '' }}>BND ($)</option>
                    <option value="IDR" {{ $currentCurrency == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 0.5rem; cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter
                </button>
                <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" style="background: #10b981; color: white; border: none; padding: 0.625rem 1.25rem; border-radius: 0.5rem; cursor: pointer; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
            <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem;">Total Revenue</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--primary); margin: 0;">{{ $currencySymbol }} {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
            <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem;">Total Refunded</p>
            <p style="font-size: 2rem; font-weight: 700; color: #ef4444; margin: 0;">{{ $currencySymbol }} {{ number_format($totalRefunded, 0, ',', '.') }}</p>
            <p style="font-size: 0.8rem; color: var(--text-light); margin: 0.25rem 0 0;">{{ $refundedOrdersCount }} orders</p>
        </div>
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
            <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem;">Total Transactions</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--primary); margin: 0;">{{ number_format($totalOrders) }}</p>
        </div>
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
            <p style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 0.5rem;">Items Sold</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--primary); margin: 0;">{{ number_format($totalItems) }}</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Chart -->
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--primary); margin: 0 0 1.5rem;">Daily Sales Trend</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--primary); margin: 0 0 1.5rem;">Top Selling Products</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($topProducts as $index => $item)
                    <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-gray);">
                        <div style="width: 32px; height: 32px; background: var(--light-gray); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary);">
                            {{ $index + 1 }}
                        </div>
                        <div style="flex: 1;">
                            <p style="font-weight: 600; color: var(--primary); margin: 0; font-size: 0.9rem;">
                                {{ $item->product ? $item->product->name : 'Unknown Product' }}
                            </p>
                            <p style="color: var(--text-light); font-size: 0.8rem; margin: 0.25rem 0 0;">
                                {{ $item->total_qty }} sold
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-weight: 600; font-size: 0.9rem; color: var(--primary);">
                                Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="color: var(--text-light); text-align: center;">No sales data yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Transaction Table -->
    <div style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-gray);">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: var(--primary); margin: 0;">Transaction Details</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--light-gray); text-align: left;">
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.05em;">Order ID</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.05em;">Date</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.05em;">Customer</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.05em;">Product</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); letter-spacing: 0.05em; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr style="border-bottom: 1px solid var(--border-gray);">
                            <td style="padding: 1rem 1.5rem; font-weight: 500;">
                                <a href="{{ route('admin.orders.show', $t) }}" style="color: var(--primary); text-decoration: none;">{{ $t->order_number }}</a>
                            </td>
                            <td style="padding: 1rem 1.5rem; color: var(--text-light); font-size: 0.9rem;">
                                {{ $t->created_at->format('d M Y H:i') }}
                            </td>
                            <td style="padding: 1rem 1.5rem; color: var(--text-primary);">
                                {{ $t->name }}
                            </td>
                            <td style="padding: 1rem 1.5rem; color: var(--text-primary);">
                                {{ $t->product ? $t->product->name : ($t->jersey_type ?? '-') }} 
                                <span style="color: var(--text-light); font-size: 0.85rem;">(x{{ $t->quantity }})</span>
                            </td>
                            <td style="padding: 1rem 1.5rem;">
                                <span style="
                                    display: inline-block; 
                                    padding: 0.25rem 0.5rem; 
                                    border-radius: 0.375rem; 
                                    font-size: 0.75rem; 
                                    font-weight: 600;
                                    background-color: {{ match($t->status) {
                                        'paid', 'completed', 'shipped', 'delivered' => '#dcfce7',
                                        'confirmed' => '#e0f2fe',
                                        default => '#f3f4f6'
                                    } }};
                                    color: {{ match($t->status) {
                                        'paid', 'completed', 'shipped', 'delivered' => '#166534',
                                        'confirmed' => '#075985',
                                        default => '#374151'
                                    } }};
                                ">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right; font-weight: 600;">
                                {{ $t->currency }} {{ number_format($t->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-light);">No transactions in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-gray);">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Revenue (IDR)',
                    data: @json($chartData),
                    backgroundColor: '#111827',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000) + 'k';
                                }
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
