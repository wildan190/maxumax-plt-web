@extends('layouts.app')

@section('page-title', 'Create Product')

@section('content')
    <div style="max-width: 900px;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('admin.products.index') }}" style="color: #6b7280; text-decoration: none; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.5rem;" title="Back">← Back to Products</a>
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
                        <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #111827;">Product Images</label>
                        <div id="dropzoneImages" style="border:2px dashed #d1d5db; border-radius:0.5rem; padding:1rem; text-align:center; cursor:pointer;">
                            <div style="font-weight:600; color:#111827;">Drag & drop up to 4 images</div>
                            <div style="color:#6b7280; font-size:0.9rem; margin-top:0.25rem;">or click to select</div>
                        </div>
                        <input type="file" name="images[]" accept="image/*" id="imageInput" multiple style="display:none;" />
                        <div id="imagePreviewGrid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap:0.5rem; margin-top:0.75rem;"></div>
                        <div id="imageStatusText" style="margin-top:0.5rem; color:#dc2626; font-size:0.875rem; display:none;">Maksimal 4 gambar</div>
                        <div style="margin-top:0.5rem;">
                            <button type="button" id="clearImagesBtn" style="background:#e5e7eb; color:#111827; padding:0.4rem 0.8rem; border:none; border-radius:0.375rem; font-weight:600; cursor:pointer;">Clear</button>
                        </div>
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

                    <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <label style="display: block; font-weight: 600; color: #111827; margin: 0;">Product Variants (Sizes)</label>
                            <button type="button" id="addVariantBtn" style="padding: 0.4rem 0.8rem; background: #000; color: #fff; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">+ Add Variant</button>
                        </div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Add different sizes (S, M, L, XL, etc.) with individual stock levels.</p>
                        <div id="variantsContainer"></div>
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
        (function(){
            const dz = document.getElementById('dropzoneImages');
            const input = document.getElementById('imageInput');
            const grid = document.getElementById('imagePreviewGrid');
            const status = document.getElementById('imageStatusText');
            const clearBtn = document.getElementById('clearImagesBtn');
            
            // Store current files in memory
            let currentFiles = [];
            
            function updateInputFiles(files) {
                const dt = new DataTransfer();
                files.forEach(f => dt.items.add(f));
                input.files = dt.files;
                currentFiles = Array.from(files);
            }
            
            function render(files){
                grid.innerHTML = '';
                for (let i = 0; i < files.length; i++) {
                    const f = files[i];
                    const reader = new FileReader();
                    reader.onload = function(evt){
                        const container = document.createElement('div');
                        container.style.position = 'relative';
                        container.style.width = '100%';
                        
                        const img = document.createElement('img');
                        img.src = evt.target.result;
                        img.style.width = '100%';
                        img.style.height = '80px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '0.375rem';
                        img.style.border = '1px solid #e5e7eb';
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.textContent = '×';
                        removeBtn.type = 'button';
                        removeBtn.style.position = 'absolute';
                        removeBtn.style.top = '2px';
                        removeBtn.style.right = '2px';
                        removeBtn.style.background = '#dc2626';
                        removeBtn.style.color = 'white';
                        removeBtn.style.border = 'none';
                        removeBtn.style.borderRadius = '50%';
                        removeBtn.style.width = '20px';
                        removeBtn.style.height = '20px';
                        removeBtn.style.cursor = 'pointer';
                        removeBtn.style.fontSize = '14px';
                        removeBtn.style.fontWeight = 'bold';
                        removeBtn.style.display = 'flex';
                        removeBtn.style.alignItems = 'center';
                        removeBtn.style.justifyContent = 'center';
                        removeBtn.style.lineHeight = '1';
                        removeBtn.onclick = function() {
                            removeFile(i);
                        };
                        
                        container.appendChild(img);
                        container.appendChild(removeBtn);
                        grid.appendChild(container);
                    };
                    reader.readAsDataURL(f);
                }
                status.style.display = files.length >= 4 ? 'block' : 'none';
            }
            
            function removeFile(index){
                currentFiles.splice(index, 1);
                updateInputFiles(currentFiles);
                render(currentFiles);
            }
            
            function addFiles(newFiles){
                // Filter only image files
                const imageFiles = Array.from(newFiles).filter(f => f.type.startsWith('image/'));
                if (imageFiles.length === 0) return;
                
                // Calculate remaining slots
                const remainingSlots = 4 - currentFiles.length;
                if (remainingSlots <= 0) {
                    status.textContent = 'Maksimal 4 gambar sudah tercapai';
                    status.style.display = 'block';
                    setTimeout(() => {
                        if (currentFiles.length < 4) status.style.display = 'none';
                    }, 2000);
                    return;
                }
                
                // Add new files, avoiding duplicates
                const seen = new Set();
                currentFiles.forEach(f => {
                    seen.add(`${f.name}-${f.size}`);
                });
                
                for (const file of imageFiles) {
                    if (currentFiles.length >= 4) break;
                    const key = `${file.name}-${file.size}`;
                    if (!seen.has(key)) {
                        seen.add(key);
                        currentFiles.push(file);
                    }
                }
                
                updateInputFiles(currentFiles);
                render(currentFiles);
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
                if (files.length) {
                    addFiles(files);
                }
            });
            
            input.addEventListener('change', function(e){
                // When user selects files via file picker, add them to existing files
                if (e.target.files.length) {
                    addFiles(e.target.files);
                }
            });
            
            clearBtn.addEventListener('click', function(){
                currentFiles = [];
                input.value = '';
                grid.innerHTML = '';
                status.style.display = 'none';
            });

            // Variant Management
            let variantIndex = 0;
            const variantsContainer = document.getElementById('variantsContainer');
            const addVariantBtn = document.getElementById('addVariantBtn');

            function createVariantRow(name = '', stock = 0, sku = '') {
                const row = document.createElement('div');
                row.style.display = 'grid';
                row.style.gridTemplateColumns = '2fr 1fr 2fr auto';
                row.style.gap = '0.5rem';
                row.style.marginBottom = '0.75rem';
                row.style.alignItems = 'center';
                
                row.innerHTML = `
                    <input type="text" name="variants[${variantIndex}][name]" value="${name}" placeholder="e.g., S, M, L, XL" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;" required />
                    <input type="number" name="variants[${variantIndex}][stock]" value="${stock}" min="0" placeholder="Stock" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;" required />
                    <input type="text" name="variants[${variantIndex}][sku]" value="${sku}" placeholder="SKU (optional)" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-family: monospace;" />
                    <button type="button" class="removeVariantBtn" style="padding: 0.5rem; background: #dc2626; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 600;">×</button>
                `;
                
                const removeBtn = row.querySelector('.removeVariantBtn');
                removeBtn.addEventListener('click', function() {
                    row.remove();
                });
                
                variantsContainer.appendChild(row);
                variantIndex++;
            }

            addVariantBtn.addEventListener('click', function() {
                createVariantRow();
            });

            // Add default variants for new products
            const defaultSizes = ['S', 'M', 'L', 'XL', 'XXL'];
            defaultSizes.forEach(size => {
                createVariantRow(size, 0, '');
            });
        })();
    </script>
@endsection
