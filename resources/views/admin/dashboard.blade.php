@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . '!')

@section('content')
    <div>
        <!-- Currency Toggle -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
            <form action="{{ route('dashboard') }}" method="GET" id="currencyForm">
                <select name="currency" onchange="document.getElementById('currencyForm').submit()"
                    style="padding: 0.5rem; border-radius: 0.375rem; border: 1px solid var(--border-gray); background: var(--secondary); color: var(--text-primary); font-weight: 600;">
                    <option value="MYR" {{ $currentCurrency == 'MYR' ? 'selected' : '' }}>MYR (RM)</option>
                    <option value="BND" {{ $currentCurrency == 'BND' ? 'selected' : '' }}>BND ($)</option>
                    <option value="IDR" {{ $currentCurrency == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                </select>
            </form>
        </div>

        <!-- Stats Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Stat Card: Total Orders -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3
                        style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                        Total Orders
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 24px; height: 24px; color: var(--text-light);">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                    </svg>
                </div>
                <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">
                    {{ number_format($totalOrders) }}</p>
                <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">All orders</p>
            </div>

            <!-- Stat Card: Total Revenue -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3
                        style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                        Total Revenue
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 24px; height: 24px; color: var(--text-light);">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">{{ $currencySymbol }}
                    {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">Total income</p>
            </div>

            <!-- Stat Card: Total Refunded -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3
                        style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                        Total Refunded
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 24px; height: 24px; color: var(--text-light);">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                    </svg>
                </div>
                <p style="font-size: 2.5rem; font-weight: 700; color: #ef4444; margin: 0;">{{ $currencySymbol }}
                    {{ number_format($totalRefunded, 0, ',', '.') }}</p>
                <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">{{ $refundedOrdersCount }}
                    refunded orders</p>
            </div>

            <!-- Stat Card: Growth -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3
                        style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                        Order Growth
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 24px; height: 24px; color: var(--text-light);">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 17"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
                <p
                    style="font-size: 2.5rem; font-weight: 700; color: {{ $growth >= 0 ? '#22c55e' : '#ef4444' }}; margin: 0;">
                    {{ $growth > 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                </p>
                <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">This month vs last month</p>
            </div>

            <!-- Stat Card: Products -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <h3
                        style="font-size: 0.875rem; font-weight: 600; color: var(--text-light); margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">
                        Active Products
                    </h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="width: 24px; height: 24px; color: var(--text-light);">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary); margin: 0;">{{ $activeProducts }}</p>
                <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.5rem 0 0;">Available products</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Chart Section -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0;">Revenue Chart</h2>
                    <!-- Simple Filter (Visual only for now as controller handles last 7 days) -->
                    <select
                        style="padding: 0.5rem; border-radius: 0.375rem; border: 1px solid var(--border-gray); background: var(--secondary); color: var(--text-primary);">
                        <option>Last 7 Days</option>
                    </select>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Task List Section -->
            <div
                style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0 0 1.5rem;">Task List</h2>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <!-- Task Item: Refund -->
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-gray);">
                        <div>
                            <p style="font-weight: 600; color: var(--primary); margin: 0;">Refund Requests</p>
                            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.25rem 0 0;">Waiting for
                                approval</p>
                        </div>
                        @if($pendingRefunds > 0)
                            <span
                                style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">{{ $pendingRefunds }}</span>
                        @else
                            <span
                                style="background: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 50%; font-size: 0.875rem;">✓</span>
                        @endif
                    </div>

                    <!-- Task Item: Complaints -->
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-gray);">
                        <div>
                            <p style="font-weight: 600; color: var(--primary); margin: 0;">New Complaints</p>
                            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.25rem 0 0;">
                                Refund/Replacement requests</p>
                        </div>
                        @if($pendingComplaints > 0)
                            <a href="{{ route('admin.complaints.index') }}"
                                style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; text-decoration: none;">{{ $pendingComplaints }}</a>
                        @else
                            <span
                                style="background: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 50%; font-size: 0.875rem;">✓</span>
                        @endif
                    </div>

                    <!-- Task Item: Packing -->
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-gray);">
                        <div>
                            <p style="font-weight: 600; color: var(--primary); margin: 0;">Needs Packing</p>
                            <p style="font-size: 0.875rem; color: var(--text-light); margin: 0.25rem 0 0;">Order paid</p>
                        </div>
                        @if($ordersToPack > 0)
                            <span
                                style="background: #f59e0b; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">{{ $ordersToPack }}</span>
                        @else
                            <span
                                style="background: #22c55e; color: white; padding: 0.25rem 0.5rem; border-radius: 50%; font-size: 0.875rem;">✓</span>
                        @endif
                    </div>
                </div>

                <!-- Low Stock Section -->
                <h3 style="font-size: 1rem; font-weight: 700; color: var(--primary); margin: 1.5rem 0 1rem;">Low Stock</h3>
                @if($lowStockProducts->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($lowStockProducts as $product)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: var(--text-primary); font-size: 0.9rem;">{{ $product->name }}</span>
                                <span style="color: #ef4444; font-weight: 600; font-size: 0.9rem;">{{ $product->stock }} left</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--text-light); font-size: 0.9rem;">Stock is safe.</p>
                @endif
            </div>
        </div>

        <!-- Recent Activity (Orders) -->
        <div
            style="background: var(--secondary); border: 1px solid var(--border-gray); border-radius: 0.75rem; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin: 0;">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}"
                    style="color: var(--primary); text-decoration: none; font-size: 0.9rem; font-weight: 600;">View All
                    &rarr;</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse ($recentOrders as $order)
                            <div
                                style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-gray);">
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <span style="font-weight: 700; color: var(--primary);">{{ substr($order->name, 0, 1) }}</span>
                                </div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <div>
                                            <p style="font-weight: 600; color: var(--primary); margin: 0; font-size: 0.95rem;">
                                                {{ $order->name }}
                                                <span style="font-weight: normal; color: var(--text-light);">ordered</span>
                                                {{ $order->product ? $order->product->name : 'Product Deleted' }}
                                            </p>
                                            <p style="color: var(--text-light); font-size: 0.85rem; margin: 0.25rem 0 0;">
                                                {{ $order->order_number }} • {{ $order->quantity }} pcs • {{ $order->currency }}
                                                {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div style="text-align: right;">
                                            <span style="
                                                    display: inline-block; 
                                                    padding: 0.25rem 0.5rem; 
                                                    border-radius: 0.375rem; 
                                                    font-size: 0.75rem; 
                                                    font-weight: 600;
                                                    background-color: {{ match ($order->status) {
                        'paid', 'completed', 'shipped', 'delivered' => '#dcfce7',
                        'confirmed' => '#e0f2fe',
                        'pending' => '#fef9c3',
                        'cancelled', 'rejected', 'refunded' => '#fee2e2',
                        default => '#f3f4f6'
                    } }};
                                                    color: {{ match ($order->status) {
                        'paid', 'completed', 'shipped', 'delivered' => '#166534',
                        'confirmed' => '#075985',
                        'pending' => '#854d0e',
                        'cancelled', 'rejected', 'refunded' => '#991b1b',
                        default => '#374151'
                    } }};
                                                ">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <p style="color: var(--text-light); font-size: 0.75rem; margin: 0.25rem 0 0;">
                                                {{ $order->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                @empty
                    <p style="text-align: center; color: var(--text-light); padding: 1rem;">No orders yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            const chartData = @json($chartData);
            const chartLabels = @json($chartLabels);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Revenue (IDR)',
                        data: chartData,
                        borderColor: '#000000',
                        backgroundColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#000000',
                        pointRadius: 4
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
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
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