@extends('layouts.app')

@section('page-title', 'Products')

@section('content')
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 1rem !important;
            }
            
            .header-container a {
                width: 100%;
                justify-content: center !important;
            }
            
            .desktop-table {
                display: none !important;
            }
            
            .mobile-list {
                display: block !important;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-list {
                display: none !important;
            }
        }
        
        /* Mobile Card Styles */
        .product-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .product-card.expanded {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .product-header {
            padding: 1rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: #f9fafb;
        }
        
        .product-header:active {
            background: #f3f4f6;
        }
        
        .product-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .product-body.show {
            max-height: 600px;
        }
        
        .product-details {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
            align-items: flex-start;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        
        .detail-value {
            color: #111827;
            font-size: 0.95rem;
            text-align: right;
            max-width: 65%;
            word-wrap: break-word;
        }
        
        .product-actions {
            padding: 1rem;
            background: #f9fafb;
            display: flex;
            gap: 0.5rem;
        }
        
        .chevron {
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }
        
        .chevron.rotate {
            transform: rotate(180deg);
        }
    </style>

    <div class="header-container" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">Manage and organize your preorder products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #000; color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background 0.2s;">+ Add Product</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500;">{{ session('success') }}</div>
    @endif

    <!-- Desktop Table -->
    <div class="desktop-table" style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
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
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Mobile List -->
    <div class="mobile-list">
        @forelse($products as $p)
            <div class="product-card" data-product-id="{{ $p->id }}">
                <div class="product-header" onclick="toggleProduct({{ $p->id }})">
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; color: #111827; margin-bottom: 0.25rem; font-size: 0.95rem;">
                            {{ $p->name }}
                        </div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">
                            {{ $p->jersey_type }}
                        </div>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center;">
                            <span style="font-weight: 700; color: #111827; font-size: 0.95rem;">
                                RM {{ number_format($p->price, 2) }}
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                @if($p->is_active)
                                    <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                                    <span style="color: #065f46; font-weight: 500; font-size: 0.75rem;">Active</span>
                                @else
                                    <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                                    <span style="color: #7f1d1d; font-weight: 500; font-size: 0.75rem;">Inactive</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; margin-left: 0.5rem;">
                        <svg class="chevron" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 7.5L10 12.5L15 7.5" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="product-body">
                    <div class="product-details">
                        <div class="detail-row">
                            <span class="detail-label">Type</span>
                            <span class="detail-value">{{ $p->jersey_type }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Price</span>
                            <span class="detail-value" style="font-weight: 700;">RM {{ number_format($p->price, 2) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">SKU</span>
                            <span class="detail-value" style="font-family: monospace;">{{ $p->sku ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Stock</span>
                            <span class="detail-value" style="font-weight: 600;">{{ $p->stock }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status</span>
                            <span class="detail-value">
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    @if($p->is_active)
                                        <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                                        <span style="color: #065f46; font-weight: 500; font-size: 0.875rem;">Active</span>
                                    @else
                                        <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                                        <span style="color: #7f1d1d; font-weight: 500; font-size: 0.875rem;">Inactive</span>
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="product-actions">
                        <a href="{{ route('admin.products.edit', $p) }}" style="
                            flex: 1;
                            background: #f3f4f6;
                            color: #000;
                            padding: 0.625rem;
                            border: none;
                            border-radius: 0.5rem;
                            font-size: 0.875rem;
                            font-weight: 600;
                            text-decoration: none;
                            display: block;
                            text-align: center;
                        ">
                            Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" style="flex: 1;">
                            @csrf @method('DELETE')
                            <button type="submit" style="
                                width: 100%;
                                background: #fee2e2;
                                color: #991b1b;
                                padding: 0.625rem;
                                border: none;
                                border-radius: 0.5rem;
                                font-size: 0.875rem;
                                font-weight: 600;
                                cursor: pointer;
                            " onclick="return confirm('Delete this product?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 0.75rem; padding: 3rem; text-align: center; border: 1px solid #e5e7eb;">
                <p style="color: #6b7280; font-size: 1rem; margin: 0;">
                    No products found. <a href="{{ route('admin.products.create') }}" style="color: #000; font-weight: 600;">Create one</a>
                </p>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        @if($products->count() > 0)
            <div style="padding: 1.5rem 0; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <script>
        // Toggle product card
        function toggleProduct(productId) {
            const card = document.querySelector(`[data-product-id="${productId}"]`);
            const body = card.querySelector('.product-body');
            const chevron = card.querySelector('.chevron');
            
            body.classList.toggle('show');
            chevron.classList.toggle('rotate');
            card.classList.toggle('expanded');
        }
    </script>
@endsection