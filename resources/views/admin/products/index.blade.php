@extends('layouts.app')

@section('page-title', 'Products')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin: 0;">Product Catalog</h1>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Manage and organize your preorder products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #000; color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background 0.2s;">+ Add Product</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500;">{{ session('success') }}</div>
    @endif

    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Name</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Type</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Price</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">SKU</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Stock</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                            <td style="padding: 1rem; color: #111827; font-weight: 500;">{{ $p->name }}</td>
                            <td style="padding: 1rem; color: #6b7280;">{{ $p->jersey_type }}</td>
                            <td style="padding: 1rem; color: #111827; font-weight: 500;">RM {{ number_format($p->price, 2) }}</td>
                            <td style="padding: 1rem; color: #6b7280; font-family: monospace; font-size: 0.9rem;">{{ $p->sku ?? '—' }}</td>
                            <td style="padding: 1rem; color: #111827; font-weight: 600;">{{ $p->stock }}</td>
                            <td style="padding: 1rem;">
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    @if($p->is_active)
                                        <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                                        <span style="color: #065f46; font-weight: 500; font-size: 0.85rem;">Active</span>
                                    @else
                                        <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                                        <span style="color: #7f1d1d; font-weight: 500; font-size: 0.85rem;">Inactive</span>
                                    @endif
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="{{ route('admin.products.edit', $p) }}" style="color: #000; text-decoration: none; font-weight: 500; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #f3f4f6; transition: background 0.2s; font-size: 0.875rem;">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: #fee2e2; color: #991b1b; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 500; cursor: pointer; transition: background 0.2s; font-size: 0.875rem;" onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 3rem; text-align: center; color: #6b7280;">
                                <p style="margin: 0; font-size: 1rem;">No products found. <a href="{{ route('admin.products.create') }}" style="color: #000; font-weight: 600;">Create one</a></p>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
@endsection
