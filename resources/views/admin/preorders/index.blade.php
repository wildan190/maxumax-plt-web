@extends('layouts.app')

@section('page-title', 'Preorder Management')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin: 0;">Preorder Management</h1>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Manage and monitor all preorder requests</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <form method="GET" action="{{ route('admin.preorders.index') }}" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Search by name/email..." value="{{ request('search') }}" style="padding: 0.625rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.95rem;" />
                <button type="submit" style="background: #000; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Search</button>
            </form>
            <form method="POST" action="{{ route('admin.preorders.export') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: #6b7280; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <span>📥</span> Export CSV
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #000; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Total Orders</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #000; margin: 0;">{{ $preorders->total() }}</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #f97316; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Pending</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #f97316; margin: 0;">{{ $preorders->where('status', 'pending')->count() }}</p>
        </div>
        <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border-left: 4px solid #22c55e; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 0.5rem 0; font-weight: 600; text-transform: uppercase;">Paid</p>
            <p style="font-size: 1.875rem; font-weight: 800; color: #22c55e; margin: 0;">{{ $preorders->where('status', 'paid')->count() }}</p>
        </div>
    </div>

    <!-- Table -->
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        @if($preorders->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">ID</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Nama</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Email</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Phone</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Jersey</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Size</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Qty</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Total</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Status</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preorders as $preorder)
                            <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                                <td style="padding: 1rem; color: #111827; font-weight: 600;">
                                    {{ $preorder->id }}
                                </td>
                                <td style="padding: 1rem; color: #111827;">{{ $preorder->name }}</td>
                                <td style="padding: 1rem; color: #6b7280; font-size: 0.95rem;">{{ $preorder->email ?? '—' }}</td>
                                <td style="padding: 1rem; color: #6b7280; font-size: 0.95rem;">{{ $preorder->phone }}</td>
                                <td style="padding: 1rem; color: #111827; font-size: 0.95rem;">{{ $preorder->jersey_type }}</td>
                                <td style="padding: 1rem; color: #6b7280; font-size: 0.95rem;">
                                    {{ $preorder->size }}
                                    @if($preorder->long_sleeve)
                                        <span style="background: #f0f9ff; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem;">+LS</span>
                                    @endif
                                    @if($preorder->nameset)
                                        <span style="background: #f5f3ff; color: #7c3aed; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; margin-left: 0.25rem;">+NS</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; color: #111827; font-weight: 600;">{{ $preorder->quantity }}</td>
                                <td style="padding: 1rem; color: #111827; font-weight: 700;">{{ $preorder->currency ?? 'MYR' }} {{ number_format($preorder->total_amount, 2) }}</td>
                                <td style="padding: 1rem;">
                                    @if($preorder->status === 'pending')
                                        <span style="background: #fef3c7; color: #92400e; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">Pending</span>
                                    @elseif($preorder->status === 'paid')
                                        <span style="background: #dcfce7; color: #166534; padding: 0.35rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">Paid</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem;">
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        @if($preorder->status === 'pending')
                                            <form method="POST" action="{{ route('admin.preorders.markPaid', $preorder->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background: #22c55e; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">Mark Paid</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.preorders.show', $preorder->id) }}" style="background: #3b82f6; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">View</a>
                                        <form method="POST" action="{{ route('admin.preorders.destroy', $preorder->id) }}" style="display: inline;" onsubmit="return confirm('Delete this preorder?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #ef4444; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 0.375rem; font-size: 0.8rem; font-weight: 600; cursor: pointer;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="padding: 3rem 1rem; text-align: center; color: #6b7280;">
                                    <p style="margin: 0; font-size: 1rem;">No preorders found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
                {{ $preorders->links() }}
            </div>
        @else
            <div style="padding: 3rem; text-align: center;">
                <p style="color: #6b7280; font-size: 1rem; margin: 0;">Tidak ada pre-order ditemukan</p>
                <a href="/" style="display: inline-block; margin-top: 1rem; background: #000; color: white; padding: 0.625rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600;">Mulai Pre-order</a>
            </div>
        @endif
    </div>

    <script>
        function showDetails(id) {
            alert('Detail preorder #' + id + ' — fitur detail view akan datang');
        }
    </script>
@endsection
