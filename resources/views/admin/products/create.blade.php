@extends('layouts.app')

@section('page-title', 'Create Product')

@section('content')
    <div style="max-width: 900px;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('admin.products.index') }}" style="color: #6b7280; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;" title="Back">← Back to Products</a>
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin: 0.5rem 0 0 0;">Create New Product</h1>
        </div>

        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <p style="margin: 0 0 0.5rem 0; font-weight: 600;">Please fix the errors below:</p>
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 280px; gap: 2rem;">
                <div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; transition: border-color 0.2s;" placeholder="e.g., Player Jersey Home 2024" />
                        @error('name')
                            <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Jersey Type *</label>
                        <select name="jersey_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;">
                            <option value="">-- Select --</option>
                            <option value="Player Home" {{ old('jersey_type') == 'Player Home' ? 'selected' : '' }}>Player Home</option>
                            <option value="Player Away" {{ old('jersey_type') == 'Player Away' ? 'selected' : '' }}>Player Away</option>
                            <option value="GK Home" {{ old('jersey_type') == 'GK Home' ? 'selected' : '' }}>GK Home</option>
                            <option value="GK Away" {{ old('jersey_type') == 'GK Away' ? 'selected' : '' }}>GK Away</option>
                        </select>
                        @error('jersey_type')
                            <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Description</label>
                        <textarea name="description" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; font-family: inherit; min-height: 140px; transition: border-color 0.2s;" placeholder="Add product details...">{{ old('description') }}</textarea>
                        @error('description')
                            <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 500; color: #111827;">
                                <input type="checkbox" name="available_for_preorder" value="1" {{ old('available_for_preorder') ? 'checked' : '' }} />
                                Available for Preorder
                            </label>
                        </div>
                        <div>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 500; color: #111827;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                                Active
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Product Image</label>
                        <div id="imagePreview" style="margin-bottom: 1rem; display: none;">
                            <img id="preview" src="" style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; border: 1px solid #e5e7eb;" alt="Preview" />
                        </div>
                        <input type="file" name="image" accept="image/*" id="imageInput" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; cursor: pointer;" />
                        <p style="font-size: 0.875rem; color: #6b7280; margin: 0.5rem 0 0 0;">PNG, JPG or GIF (Max 5MB)</p>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Price (MYR) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', '40.00') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" />
                        @error('price')
                            <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem; font-family: monospace;" placeholder="e.g., JER-001" />
                        @error('sku')
                            <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Stock Quantity</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 1rem;" />
                        @error('stock')
                            <span style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button type="submit" style="padding: 0.75rem 1.5rem; background: #000; color: #fff; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: background 0.2s; font-size: 1rem;">Create Product</button>
                <a href="{{ route('admin.products.index') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: #e5e7eb; color: #111827; border: none; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background 0.2s;">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('imageInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('preview').src = event.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
