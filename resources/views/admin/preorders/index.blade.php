@extends('layouts.app')

@section('page-title', 'Preorder Management')

@section('content')

    <style>
        /* ===== HEADER ===== */
        .page-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-header form {
            display: flex;
            gap: 0.5rem;
        }

        /* ===== SUMMARY ===== */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        /* ===== TABLE ===== */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* ===== MOBILE ===== */
        @media (max-width: 768px) {
            table {
                min-width: 1100px;
            }
        }

        @media (max-width: 640px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-header form {
                flex-direction: column;
            }

            .page-header input,
            .page-header button {
                width: 100%;
            }

            .hide-mobile {
                display: none;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons button,
            .action-buttons a {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <p style="color:#6b7280;margin:0.5rem 0 0 0;font-size:0.95rem;">
                Manage and monitor all preorder requests
            </p>
        </div>

        <form method="GET" action="{{ route('admin.preorders.index') }}">
            <input type="text" name="search" placeholder="Search by name/email..." value="{{ request('search') }}"
                style="padding:0.625rem 1rem;border:1px solid #e5e7eb;border-radius:0.5rem;font-size:0.95rem;">
            <button type="submit"
                style="background:#000;color:#fff;padding:0.625rem 1.5rem;border:none;border-radius:0.5rem;font-weight:600;">
                Search
            </button>
        </form>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid">
        <div
            style="background:#fff;padding:1.5rem;border-radius:.75rem;border-left:4px solid #000;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <p style="font-size:.875rem;color:#6b7280;font-weight:600;text-transform:uppercase;">Total Orders</p>
            <p style="font-size:1.875rem;font-weight:800;margin:0;">{{ $counts['total'] }}</p>
        </div>

        <div
            style="background:#fff;padding:1.5rem;border-radius:.75rem;border-left:4px solid #f97316;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <p style="font-size:.875rem;color:#6b7280;font-weight:600;text-transform:uppercase;">Pending</p>
            <p style="font-size:1.875rem;font-weight:800;color:#f97316;margin:0;">{{ $counts['pending'] }}</p>
        </div>

        <div
            style="background:#fff;padding:1.5rem;border-radius:.75rem;border-left:4px solid #6366f1;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <p style="font-size:.875rem;color:#6b7280;font-weight:600;text-transform:uppercase;">Confirmed</p>
            <p style="font-size:1.875rem;font-weight:800;color:#6366f1;margin:0;">{{ $counts['confirmed'] }}</p>
        </div>

        <div
            style="background:#fff;padding:1.5rem;border-radius:.75rem;border-left:4px solid #22c55e;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <p style="font-size:.875rem;color:#6b7280;font-weight:600;text-transform:uppercase;">Paid</p>
            <p style="font-size:1.875rem;font-weight:800;color:#22c55e;margin:0;">{{ $counts['paid'] }}</p>
        </div>
    </div>

    <!-- TABLE -->
    <div style="background:#fff;border-radius:.75rem;box-shadow:0 1px 3px rgba(0,0,0,.05);overflow:hidden;">
        @if($preorders->count())
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                            <th>ID</th>
                            <th>Nama</th>
                            <th class="hide-mobile">Email</th>
                            <th class="hide-mobile">Phone</th>
                            <th class="hide-mobile">Jersey</th>
                            <th>Size</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($preorders as $preorder)
                            <tr style="border-bottom:1px solid #e5e7eb;">
                                <td style="padding:1rem;font-weight:600;">{{ $preorder->id }}</td>
                                <td style="padding:1rem;">{{ $preorder->name }}</td>
                                <td class="hide-mobile" style="padding:1rem;">{{ $preorder->email ?? '—' }}</td>
                                <td class="hide-mobile" style="padding:1rem;">{{ $preorder->phone }}</td>
                                <td class="hide-mobile" style="padding:1rem;">{{ $preorder->jersey_type }}</td>
                                <td style="padding:1rem;">{{ $preorder->size }}</td>
                                <td style="padding:1rem;font-weight:600;">{{ $preorder->quantity }}</td>
                                <td style="padding:1rem;font-weight:700;">{{ $preorder->currency ?? 'MYR' }}
                                    {{ number_format($preorder->total_amount, 2) }}</td>
                                <td style="padding:1rem;">
                                    @if($preorder->status === 'pending')
                                        <span
                                            style="background:#fef3c7;color:#92400e;padding:.35rem .75rem;border-radius:.5rem;font-size:.85rem;font-weight:600;">Pending</span>
                                    @elseif($preorder->status === 'confirmed')
                                        <span
                                            style="background:#e0e7ff;color:#3730a3;padding:.35rem .75rem;border-radius:.5rem;font-size:.85rem;font-weight:600;">Confirmed</span>
                                    @else
                                        <span
                                            style="background:#dcfce7;color:#166534;padding:.35rem .75rem;border-radius:.5rem;font-size:.85rem;font-weight:600;">Paid</span>
                                    @endif
                                </td>

                                <td style="padding:1rem;">
                                    <div class="action-buttons">
                                        @if($preorder->status === 'pending')
                                            <form method="POST" action="{{ route('admin.preorders.confirm', $preorder) }}"
                                                class="js-confirm" data-title="Confirm this preorder?"
                                                data-text="Status akan diubah menjadi confirmed.">
                                                @csrf
                                                <button type="submit"
                                                    style="background:#6366f1;color:#fff;padding:.4rem .8rem;border:none;border-radius:.375rem;font-size:.8rem;">Confirm</button>
                                            </form>
                                        @elseif($preorder->status === 'confirmed')
                                            <form method="POST" action="{{ route('admin.preorders.markPaid', $preorder) }}"
                                                class="js-confirm" data-title="Mark as paid?"
                                                data-text="Status akan diubah menjadi paid.">
                                                @csrf
                                                <button type="submit"
                                                    style="background:#22c55e;color:#fff;padding:.4rem .8rem;border:none;border-radius:.375rem;font-size:.8rem;">Mark
                                                    Paid</button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.preorders.show', $preorder) }}"
                                            style="background:#3b82f6;color:#fff;padding:.4rem .8rem;border-radius:.375rem;font-size:.8rem;text-decoration:none;">View</a>

                                        <form method="POST" action="{{ route('admin.preorders.destroy', $preorder) }}"
                                            class="js-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="background:#ef4444;color:#fff;padding:.4rem .8rem;border:none;border-radius:.375rem;font-size:.8rem;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="padding:3rem;text-align:center;color:#6b7280;">
                                    No preorders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding:1.5rem;border-top:1px solid #e5e7eb;display:flex;justify-content:center;">
                {{ $preorders->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.js-confirm').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                Swal.fire({
                    title: form.dataset.title,
                    text: form.dataset.text,
                    icon: 'question',
                    showCancelButton: true
                }).then(r => r.isConfirmed && form.submit());
            });
        });

        document.querySelectorAll('.js-delete').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();
                Swal.fire({
                    title: 'Delete this preorder?',
                    text: 'Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true
                }).then(r => r.isConfirmed && form.submit());
            });
        });
    </script>

@endsection