@extends('layouts.app')

@section('page-title', 'Edit Product')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('admin.products.index') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; background: #f3f4f6; border-radius: 0.5rem; text-decoration: none; color: #111827; font-weight: 600; transition: background 0.2s;">←</a>
            <div>
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
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0 0 1rem 0; display: flex; align-items: center; gap: 0.5rem;">🖼️ Product Images</h2>
                        
                        <div style="margin-bottom: 1rem;">
                            @if($product->image_path || $product->images->count())
                                <div style="margin-bottom: 1rem; border-radius: 0.5rem; overflow: hidden; border: 1px solid #e5e7eb; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 200px;">
                                    @php
                                        $paths = [];
                                        if ($product->image_path) $paths[] = $product->image_path;
                                        foreach($product->images as $img) { $paths[] = $img->path; }
                                        $first = $paths[0] ?? null;
                                    @endphp
                                    @if($first)
                                        <img src="{{ asset('storage/' . $first) }}" style="max-width: 100%; max-height: 200px; object-fit: contain; padding: 0.5rem;" alt="{{ $product->name }}" />
                                    @else
                                        <p style="color:#9ca3af; margin:0;">No image</p>
                                    @endif
                                </div>
                            @else
                                <div style="margin-bottom: 1rem; border-radius: 0.5rem; border: 2px dashed #d1d5db; padding: 2rem; text-align: center; background: #f9fafb;">
                                    <p style="color: #9ca3af; margin: 0; font-size: 2rem;">📸</p>
                                    <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.9rem;">No image</p>
                                </div>
                            @endif
                        </div>

                        <div style="margin-bottom: 0.5rem;">
                            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Upload Images</label>
                            <div id="dropzoneImagesEdit" style="border:2px dashed #d1d5db; border-radius:0.5rem; padding:1rem; text-align:center; cursor:pointer;">
                                <div style="font-weight:600; color:#111827;">Drag & drop up to 4 images</div>
                                <div style="color:#6b7280; font-size:0.9rem; margin-top:0.25rem;">or click to select</div>
                            </div>
                            <input type="file" name="images[]" accept="image/*" id="imageInputEdit" multiple style="display:none;" />
                            <div id="imagePreviewGridEdit" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap:0.5rem; margin-top:0.75rem;"></div>
                            <div id="imageStatusTextEdit" style="margin-top:0.5rem; color:#dc2626; font-size:0.875rem; display:none;">Maksimal 4 gambar</div>
                            <div style="margin-top:0.5rem;">
                                <button type="button" id="clearImagesBtnEdit" style="background:#e5e7eb; color:#111827; padding:0.4rem 0.8rem; border:none; border-radius:0.375rem; font-weight:600; cursor:pointer;">Clear</button>
                            </div>
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
    <script>
        (function(){
            const dz = document.getElementById('dropzoneImagesEdit');
            const input = document.getElementById('imageInputEdit');
            const grid = document.getElementById('imagePreviewGridEdit');
            const status = document.getElementById('imageStatusTextEdit');
            const clearBtn = document.getElementById('clearImagesBtnEdit');
            function render(files){
                grid.innerHTML = '';
                const max = Math.min(files.length, 4);
                for (let i = 0; i < max; i++) {
                    const f = files[i];
                    const reader = new FileReader();
                    reader.onload = function(evt){
                        const img = document.createElement('img');
                        img.src = evt.target.result;
                        img.style.width = '100%';
                        img.style.height = '80px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '0.375rem';
                        img.style.border = '1px solid #e5e7eb';
                        grid.appendChild(img);
                    };
                    reader.readAsDataURL(f);
                }
                status.style.display = files.length > 4 ? 'block' : 'none';
            }
            function setFiles(fileList){
                const dt = new DataTransfer();
                const arr = Array.from(fileList).slice(0,4);
                arr.forEach(f => dt.items.add(f));
                input.files = dt.files;
                render(arr);
            }
            dz.addEventListener('click', function(){
                input.click();
            });
            dz.addEventListener('dragover', function(e){
                e.preventDefault();
                dz.style.background = '#f9fafb';
            });
            dz.addEventListener('dragleave', function(){
                dz.style.background = 'transparent';
            });
            dz.addEventListener('drop', function(e){
                e.preventDefault();
                dz.style.background = 'transparent';
                const files = e.dataTransfer.files;
                const imgs = Array.from(files).filter(f => f.type.startsWith('image/'));
                if (imgs.length) setFiles(imgs);
            });
            input.addEventListener('change', function(e){
                const files = Array.from(e.target.files).filter(f => f.type.startsWith('image/'));
                if (files.length) setFiles(files);
            });
            clearBtn.addEventListener('click', function(){
                input.value = '';
                grid.innerHTML = '';
                status.style.display = 'none';
            });
        })();
    </script>
@endsection
