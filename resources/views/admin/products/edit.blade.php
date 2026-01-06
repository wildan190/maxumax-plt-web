@extends('layouts.app')

@section('page-title', 'Edit Product')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('admin.products.index') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; background: #f3f4f6; border-radius: 0.5rem; text-decoration: none; color: #111827; font-weight: 600; transition: background 0.2s;">←</a>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin: 0;">Edit Product</h1>
                <p style="color: #6b7280; margin: 0.25rem 0 0 0;">Update product information and settings</p>
            </div>
        </div>

        @if($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #dc2626; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                <p style="margin: 0 0 0.75rem 0; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">⚠️ Please fix the errors below:</p>
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $err)
                        <li style="margin-bottom: 0.35rem;">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Main Content Grid -->
            <div style="display: grid; grid-template-columns: 1fr 320px; gap: 2rem;">
                
                <!-- Left Column: Form Fields -->
                <div>
                    <!-- Basic Info Section -->
                    <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; margin-bottom: 1.5rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem;">📦 Basic Information</h2>
                        
                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">Product Name *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: border-color 0.2s; background: white;" />
                            @error('name')
                                <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">Jersey Type *</label>
                            <select name="jersey_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; background: white; cursor: pointer;">
                                <option value="">-- Select Jersey Type --</option>
                                <option value="Player Home" {{ old('jersey_type', $product->jersey_type) == 'Player Home' ? 'selected' : '' }}>Player Home</option>
                                <option value="Player Away" {{ old('jersey_type', $product->jersey_type) == 'Player Away' ? 'selected' : '' }}>Player Away</option>
                                <option value="GK Home" {{ old('jersey_type', $product->jersey_type) == 'GK Home' ? 'selected' : '' }}>GK Home</option>
                                <option value="GK Away" {{ old('jersey_type', $product->jersey_type) == 'GK Away' ? 'selected' : '' }}>GK Away</option>
                            </select>
                            @error('jersey_type')
                                <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">Description</label>
                            <textarea name="description" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; font-family: inherit; min-height: 120px; transition: border-color 0.2s; background: white;" placeholder="Enter product description...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing & Inventory Section -->
                    <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; margin-bottom: 1.5rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.5rem;">💰 Pricing & Inventory</h2>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">Price (MYR) *</label>
                                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; background: white;" />
                                @error('price')
                                    <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">Stock Quantity</label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; background: white;" />
                                @error('stock')
                                    <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div style="margin-bottom: 0;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; font-family: monospace; background: white;" placeholder="e.g., JER-2026-001" />
                            @error('sku')
                                <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">✓ Status</h2>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.75rem; border-radius: 0.5rem; transition: background 0.2s; user-select: none;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem; cursor: pointer;" />
                                <span style="font-weight: 500; color: #111827;">Active Product</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.75rem; border-radius: 0.5rem; transition: background 0.2s; user-select: none;">
                                <input type="checkbox" name="available_for_preorder" value="1" {{ old('available_for_preorder', $product->available_for_preorder) ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem; cursor: pointer;" />
                                <span style="font-weight: 500; color: #111827;">Preorder Enabled</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Image & Preview -->
                <div>
                    <div style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; position: sticky; top: 1rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">🖼️ Product Image</h2>
                        
                        <div style="margin-bottom: 1rem;">
                            @if($product->image_path)
                                <div style="margin-bottom: 1rem; border-radius: 0.5rem; overflow: hidden; border: 1px solid #e5e7eb; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 200px;">
                                    <img src="{{ asset('storage/' . $product->image_path) }}" style="max-width: 100%; max-height: 200px; object-fit: contain; padding: 0.5rem;" alt="{{ $product->name }}" />
                                </div>
                            @else
                                <div style="margin-bottom: 1rem; border-radius: 0.5rem; border: 2px dashed #d1d5db; padding: 2rem; text-align: center; background: #f9fafb;">
                                    <p style="color: #9ca3af; margin: 0; font-size: 2rem;">📸</p>
                                    <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.9rem;">No image</p>
                                </div>
                            @endif
                        </div>

                        <div style="margin-bottom: 0.5rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827; font-size: 0.95rem;">Upload New Image</label>
                            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; cursor: pointer; background: white;" />
                        </div>
                        <p style="font-size: 0.8rem; color: #9ca3af; margin: 0.5rem 0 0 0;">PNG, JPG, GIF (max 5MB)</p>

                        <!-- Quick Stats -->
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                            <h3 style="font-size: 0.9rem; font-weight: 600; color: #6b7280; margin: 0 0 0.75rem 0; text-transform: uppercase; letter-spacing: 0.05em;">Quick Stats</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.9rem;">
                                <div style="background: #f3f4f6; padding: 0.75rem; border-radius: 0.5rem;">
                                    <p style="color: #6b7280; margin: 0; font-size: 0.8rem;">ID</p>
                                    <p style="color: #111827; margin: 0.25rem 0 0 0; font-weight: 600; font-family: monospace; font-size: 0.85rem;">{{ substr($product->uuid, 0, 8) }}...</p>
                                </div>
                                <div style="background: #f3f4f6; padding: 0.75rem; border-radius: 0.5rem;">
                                    <p style="color: #6b7280; margin: 0; font-size: 0.8rem;">Stock</p>
                                    <p style="color: #111827; margin: 0.25rem 0 0 0; font-weight: 600;">{{ $product->stock ?? 0 }} units</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button type="submit" style="padding: 0.875rem 1.75rem; background: #000; color: white; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                    💾 Save Changes
                </button>
                <a href="{{ route('admin.products.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.75rem; background: #e5e7eb; color: #111827; border: none; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 1rem; transition: background 0.2s; cursor: pointer;">
                    ✕ Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
