@extends('layouts.app')

@section('page-title', 'Create Product')
@section('hide-page-header', true)

@section('content')
    <div style="width: 100%;">
        <div style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('admin.products.index') }}" 
                   style="display: flex; align-items: center; justify-content: center; width: 2.5rem; height: 2.5rem; background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; color: #111827; text-decoration: none; transition: all 0.2s;"
                   title="Back">
                    <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i>
                </a>
            </div>
        </div>

        @if($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1.25rem; border-radius: 0.75rem; margin-bottom: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
                    <p style="margin: 0; font-weight: 700; text-transform: uppercase; font-size: 0.875rem; tracking: 0.05em;">Validation Errors Detected</p>
                </div>
                <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem; font-weight: 500;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start;">
                <!-- Main Content Column -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- Basic Info Card -->
                    <div style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                        <div style="padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 2.5rem; height: 2.5rem; background: #e0e7ff; color: #4f46e5; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                <i data-feather="box" style="width: 20px; height: 20px;"></i>
                            </div>
                            <h2 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Basic Information</h2>
                        </div>
                        <div style="padding: 1.5rem;">
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Product Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                       style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 500; transition: all 0.2s;" 
                                       placeholder="e.g., Player Jersey Home 2024" />
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Main Category *</label>
                                <select name="category" id="categorySelect" required
                                        style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 500; background: #f9fafb; cursor: pointer;">
                                    <option value="">-- Select Category --</option>
                                    @foreach(['Jerseys', 'Polos', 'Outerwear', 'Tracksuits', 'Pants', 'Base Layer', 'Accessories', 'Cotton'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="margin-bottom: 1.5rem;" id="collectionsContainer">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Sport / Collections (Select one or more)</label>
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; background: #f9fafb; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e5e7eb;">
                                    @foreach(['Football Series', 'Golf Series', 'Fishing Series', 'Basketball Series', 'Outdoor Series', 'Run & Training Series', 'Casual / Lifestyle'] as $sport)
                                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #374151; cursor: pointer;">
                                            <input type="checkbox" name="collections[]" value="{{ $sport }}" 
                                                {{ is_array(old('collections')) && in_array($sport, old('collections')) ? 'checked' : '' }}
                                                style="width: 1rem; height: 1rem; border-radius: 0.25rem;">
                                            {{ $sport }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Material</label>
                                    <select name="material" style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; color: #111827; font-weight: 500; background: #f9fafb;">
                                        <option value="">-- Select Material --</option>
                                        @foreach(['Polyester', 'Cotton', 'Dry-fit', 'Compression'] as $mat)
                                            <option value="{{ $mat }}" {{ old('material') == $mat ? 'selected' : '' }}>{{ $mat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Gender</label>
                                    <select name="gender" style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; color: #111827; font-weight: 500; background: #f9fafb;">
                                        <option value="">-- Select Gender --</option>
                                        @foreach(['Men', 'Women', 'Unisex'] as $gen)
                                            <option value="{{ $gen }}" {{ old('gender') == $gen ? 'selected' : '' }}>{{ $gen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Fit</label>
                                    <select name="fit" style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; color: #111827; font-weight: 500; background: #f9fafb;">
                                        <option value="">-- Select Fit --</option>
                                        @foreach(['Regular Fit', 'Slim Fit', 'Oversized', 'Compression'] as $fit)
                                            <option value="{{ $fit }}" {{ old('fit') == $fit ? 'selected' : '' }}>{{ $fit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Color</label>
                                    <input type="text" name="color" value="{{ old('color') }}" placeholder="e.g. Black, Navy"
                                           style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; color: #111827; font-weight: 500;">
                                </div>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Jersey Type (Optional)</label>
                                <select name="jersey_type" 
                                        style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 500; background: #f9fafb; cursor: pointer;">
                                    <option value="">-- Select Type --</option>
                                    <option value="Player Home" {{ old('jersey_type') == 'Player Home' ? 'selected' : '' }}>Player Home</option>
                                    <option value="Player Away" {{ old('jersey_type') == 'Player Away' ? 'selected' : '' }}>Player Away</option>
                                    <option value="GK Home" {{ old('jersey_type') == 'GK Home' ? 'selected' : '' }}>GK Home</option>
                                    <option value="GK Away" {{ old('jersey_type') == 'GK Away' ? 'selected' : '' }}>GK Away</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Description</label>
                                <textarea name="description" 
                                          style="width: 100%; padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; color: #111827; font-weight: 500; font-family: inherit; min-height: 120px; transition: all 0.2s;" 
                                          placeholder="Add compelling product details...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Variants Card -->
                    <div style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                        <div style="padding: 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 2.5rem; height: 2.5rem; background: #ecfdf5; color: #059669; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                    <i data-feather="layers" style="width: 20px; height: 20px;"></i>
                                </div>
                                <h2 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Variants (Sizes)</h2>
                            </div>
                            <button type="button" id="addVariantBtn" 
                                    style="padding: 0.5rem 1rem; background: #111827; color: white; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add Size
                            </button>
                        </div>
                        <div style="padding: 1.5rem;">
                            <p style="color: #6b7280; font-size: 0.875rem; margin: 0 0 1.5rem 0; font-weight: 500;">
                                Define available sizes and their respective inventory levels.
                            </p>
                            
                            <!-- Pre-order Hint -->
                            <div id="preorderHint" style="display: none; margin-bottom: 1.5rem; padding: 1rem; background: #eff6ff; border: 1px solid #dbeafe; border-radius: 0.75rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                                <i data-feather="info" style="width: 18px; height: 18px; color: #3b82f6; flex-shrink: 0; margin-top: 0.125rem;"></i>
                                <p style="margin: 0; font-size: 0.8125rem; color: #1e40af; font-weight: 500; line-height: 1.4;">
                                    <strong>Pre-order Mode Active:</strong> You can still define available <strong>Sizes</strong> for this product. However, individual stock management is disabled as pre-order items are manufactured on demand.
                                </p>
                            </div>

                            <div id="variantsContainer" style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <!-- Variant rows will be injected here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem; position: sticky; top: 1.5rem;">
                    <!-- Media Card -->
                    <div style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                        <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="image" style="width: 18px; height: 18px; color: #6b7280;"></i>
                            <h2 style="font-size: 0.875rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Product Gallery</h2>
                        </div>
                        <div style="padding: 1.25rem;">
                            <div id="dropzoneImages" 
                                 style="border: 2px dashed #e5e7eb; border-radius: 1rem; padding: 2rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #f9fafb;"
                                 onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#eff6ff';"
                                 onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='#f9fafb';">
                                <div style="width: 3rem; height: 3rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                                    <i data-feather="upload-cloud" style="width: 20px; height: 20px; color: #3b82f6;"></i>
                                </div>
                                <p style="font-size: 0.875rem; font-weight: 700; color: #111827; margin: 0;">Upload Images</p>
                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0.25rem 0 0 0;">PNG, JPG up to 4 files</p>
                            </div>
                            <input type="file" name="images[]" accept="image/*" id="imageInput" multiple style="display:none;" />
                            
                            <div id="imagePreviewGrid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap:0.75rem; margin-top:1.25rem;" data-draggable-group="create-images"></div>
                            
                            <div id="imageStatusText" style="margin-top:0.75rem; padding: 0.75rem; background: #fef2f2; border: 1px solid #fee2e2; color: #ef4444; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; display: none;">
                                Maximum 4 images reached.
                            </div>
                            
                            <div style="margin-top: 1rem; display: flex; justify-content: center;">
                                <button type="button" id="clearImagesBtn" 
                                        style="font-size: 0.75rem; font-weight: 700; color: #ef4444; background: none; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Clear All
                                </button>
                            </div>
                            <p style="font-size: 0.7rem; color: #9ca3af; margin: 1rem 0 0 0; text-align: center; font-style: italic;">💡 Tip: Drag images to reorder</p>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
                        <div style="padding: 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 0.5rem;">
                            <i data-feather="tag" style="width: 18px; height: 18px; color: #6b7280;"></i>
                            <h2 style="font-size: 0.875rem; font-weight: 700; color: #111827; margin: 0; text-transform: uppercase; letter-spacing: 0.05em;">Pricing & Stock</h2>
                        </div>
                        <div style="padding: 1.25rem;">
                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.375rem; letter-spacing: 0.05em;">Base Price (MYR)</label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: 700; color: #9ca3af; font-size: 0.875rem;">RM</span>
                                    <input type="number" step="0.01" name="price" value="{{ old('price', '40.00') }}" required 
                                           style="width: 100%; padding: 0.75rem 0.75rem 0.75rem 3rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; font-weight: 700; color: #111827;" />
                                </div>
                            </div>

                            <div style="margin-bottom: 1.25rem;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #6b7280; margin-bottom: 0.375rem; letter-spacing: 0.05em;">Initial Total Stock</label>
                                <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" 
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 1rem; font-weight: 600; color: #111827;" />
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.75rem; padding-top: 0.5rem;">
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" name="available_for_preorder" value="1" {{ old('available_for_preorder') ? 'checked' : '' }} 
                                           style="width: 1.125rem; height: 1.125rem; border-radius: 0.375rem; cursor: pointer;" />
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Enable Pre-order</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                                           style="width: 1.125rem; height: 1.125rem; border-radius: 0.375rem; cursor: pointer;" />
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Market Visibility (Active)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <button type="submit" 
                                style="width: 100%; padding: 1rem; background: #111827; color: white; border: none; border-radius: 1rem; font-weight: 800; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                            Initialize Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" 
                           style="width: 100%; padding: 1rem; background: white; border: 1px solid #e5e7eb; color: #6b7280; border-radius: 1rem; font-weight: 700; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.1em; text-decoration: none; text-align: center; transition: all 0.2s;">
                            Discard Changes
                        </a>
                    </div>
                </div>
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
            
            let currentFiles = [];
            
            function updateInputFiles(files) {
                const dt = new DataTransfer();
                files.forEach(f => dt.items.add(f));
                input.files = dt.files;
                currentFiles = Array.from(files);
            }
            
            function render(files){
                grid.innerHTML = '';
                files.forEach((f, i) => {
                    const reader = new FileReader();
                    reader.onload = function(evt){
                        const container = document.createElement('div');
                        container.style.cssText = 'position: relative; aspect-ratio: 1; border-radius: 0.75rem; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 2px 4px rgba(0,0,0,0.05);';
                        
                        const img = document.createElement('img');
                        img.src = evt.target.result;
                        img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; pointer-events: none;';
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.innerHTML = '<i data-feather="x" style="width: 14px; height: 14px;"></i>';
                        removeBtn.type = 'button';
                        removeBtn.style.cssText = 'position: absolute; top: 0.5rem; right: 0.5rem; width: 1.5rem; height: 1.5rem; background: rgba(239, 68, 68, 0.9); color: white; border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);';
                        removeBtn.onclick = () => removeFile(i);
                        
                        // Add drag attributes for reordering
                        container.draggable = true;
                        container.dataset.fileIndex = i;
                        container.style.cursor = 'grab';
                        container.addEventListener('dragstart', handleDragStart);
                        container.addEventListener('dragend', handleDragEnd);
                        container.addEventListener('dragover', handleDragOver);
                        container.addEventListener('drop', handleDrop);
                        container.addEventListener('dragenter', handleDragEnter);
                        container.addEventListener('dragleave', handleDragLeave);
                        
                        if (i === 0) {
                            const badge = document.createElement('div');
                            badge.textContent = 'MAIN';
                            badge.style.cssText = 'position: absolute; bottom: 0.5rem; right: 0.5rem; background: #3b82f6; color: white; padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.625rem; font-weight: 700; z-index: 5;';
                            container.appendChild(badge);
                        }
                        
                        container.appendChild(img);
                        container.appendChild(removeBtn);
                        grid.appendChild(container);
                        if (typeof feather !== 'undefined') feather.replace();
                    };
                    reader.readAsDataURL(f);
                });
                status.style.display = files.length >= 4 ? 'block' : 'none';
            }
            
            function removeFile(index){
                currentFiles.splice(index, 1);
                updateInputFiles(currentFiles);
                render(currentFiles);
            }
            
            function addFiles(newFiles){
                const imageFiles = Array.from(newFiles).filter(f => f.type.startsWith('image/'));
                if (imageFiles.length === 0) return;
                
                const seen = new Set(currentFiles.map(f => `${f.name}-${f.size}`));
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
            
            dz.onclick = () => input.click();
            dz.ondragover = (e) => { e.preventDefault(); dz.style.borderColor = '#3b82f6'; };
            dz.ondragleave = () => { dz.style.borderColor = '#e5e7eb'; };
            dz.ondrop = (e) => { e.preventDefault(); addFiles(e.dataTransfer.files); };
            input.onchange = (e) => addFiles(e.target.files);
            clearBtn.onclick = () => { currentFiles = []; updateInputFiles([]); render([]); };
            
            // Drag and Drop Reordering
            let draggedIndex = null;
            let draggedOverIndex = null;
            
            function handleDragStart(e) {
                draggedIndex = parseInt(this.dataset.fileIndex);
                this.style.opacity = '0.5';
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', draggedIndex);
            }
            
            function handleDragEnd(e) {
                document.querySelectorAll('#imagePreviewGrid > div').forEach(el => {
                    el.style.opacity = '1';
                    el.style.borderColor = '#e5e7eb';
                    el.style.background = '';
                });
                draggedIndex = null;
                draggedOverIndex = null;
            }
            
            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            }
            
            function handleDragEnter(e) {
                if (this !== grid) {
                    this.style.borderColor = '#3b82f6';
                    this.style.background = '#eff6ff';
                }
            }
            
            function handleDragLeave(e) {
                this.style.borderColor = '#e5e7eb';
                this.style.background = '';
            }
            
            function handleDrop(e) {
                e.preventDefault();
                const targetIndex = parseInt(this.dataset.fileIndex);
                
                if (draggedIndex !== null && draggedIndex !== targetIndex) {
                    // Reorder array
                    const movedItem = currentFiles.splice(draggedIndex, 1)[0];
                    currentFiles.splice(targetIndex, 0, movedItem);
                    
                    updateInputFiles(currentFiles);
                    render(currentFiles);
                }
                
                this.style.borderColor = '#e5e7eb';
                this.style.background = '';
            }

            // Variant Management
            let variantIndex = 0;
            const container = document.getElementById('variantsContainer');
            const addBtn = document.getElementById('addVariantBtn');

            function createRow(name = '', stock = 0) {
                const row = document.createElement('div');
                row.style.cssText = 'display: grid; grid-template-columns: 2fr 1.5fr auto; gap: 0.75rem; align-items: center; padding: 0.75rem; background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 1rem;';
                
                row.innerHTML = `
                    <div>
                        <input type="text" name="variants[${variantIndex}][name]" value="${name}" placeholder="Size (e.g. XL)" 
                               style="width: 100%; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.625rem; font-size: 0.875rem; font-weight: 700;" required />
                    </div>
                    <div>
                        <input type="number" name="variants[${variantIndex}][stock]" value="${stock}" min="0" placeholder="Qty" 
                               style="width: 100%; padding: 0.625rem; border: 1px solid #e5e7eb; border-radius: 0.625rem; font-size: 0.875rem; font-weight: 700;" required />
                    </div>
                    <button type="button" class="remove-v" style="width: 2.25rem; height: 2.25rem; background: #fee2e2; color: #ef4444; border: none; border-radius: 0.625rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                    </button>
                `;
                
                row.querySelector('.remove-v').onclick = () => row.remove();
                container.appendChild(row);
                variantIndex++;
                if (typeof feather !== 'undefined') feather.replace();
            }

            addBtn.onclick = () => createRow();

            // Default variants
            ['S', 'M', 'L', 'XL', 'XXL'].forEach(s => createRow(s, 0));
            
            // MutationObserver to ensure new rows respect preorder state
            const stateObserver = new MutationObserver(() => {
                const isPreorder = document.querySelector('input[name="available_for_preorder"]').checked;
                if (isPreorder) {
                    const stocks = variantsSection.querySelectorAll('input[name*="[stock]"]');
                    stocks.forEach(s => {
                        s.disabled = true;
                        s.style.opacity = '0.5';
                        s.style.background = '#f3f4f6';
                        s.style.cursor = 'not-allowed';
                        s.value = 0;
                    });
                }
            });
            stateObserver.observe(container, { childList: true });

            // Dynamic Stock Summation
            function updateTotalStock() {
                const stockInputs = container.querySelectorAll('input[name*="[stock]"]');
                let total = 0;
                stockInputs.forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                document.querySelector('input[name="stock"]').value = total;
            }

            container.addEventListener('input', function(e) {
                if (e.target.name && e.target.name.includes('[stock]')) {
                    updateTotalStock();
                }
            });

            // Handle removal as well via MutationObserver or by wrapping removal
            const observer = new MutationObserver(updateTotalStock);
            observer.observe(container, { childList: true });

            updateTotalStock();

            // Pre-order Toggle Logic
            const preOrderToggle = document.querySelector('input[name="available_for_preorder"]');
            const variantsSection = document.getElementById('variantsContainer');
            const addVariantBtn = document.getElementById('addVariantBtn');
            const masterStockInput = document.querySelector('input[name="stock"]');
            const hint = document.getElementById('preorderHint');

            function togglePreorderMode() {
                const isPreorder = preOrderToggle.checked;
                
                // Toggle Hint
                if (hint) hint.style.display = isPreorder ? 'flex' : 'none';
                
                // Toggle Stock inputs in variants
                const stockInputs = variantsSection.querySelectorAll('input[name*="[stock]"]');
                stockInputs.forEach(el => {
                    el.disabled = isPreorder;
                    el.style.opacity = isPreorder ? '0.5' : '1';
                    el.style.background = isPreorder ? '#f3f4f6' : 'white';
                    el.style.cursor = isPreorder ? 'not-allowed' : 'auto';
                    if (isPreorder) el.value = 0;
                });

                // Keep Name inputs enabled
                const otherInputs = variantsSection.querySelectorAll('input[name*="[name]"]');
                otherInputs.forEach(el => {
                    el.disabled = false;
                    el.style.opacity = '1';
                    el.style.background = 'white';
                    el.style.cursor = 'auto';
                });

                // Keep Add / Remove Buttons enabled
                const buttons = variantsSection.querySelectorAll('button');
                buttons.forEach(el => {
                    el.disabled = false;
                    el.style.opacity = '1';
                    el.style.cursor = 'pointer';
                });

                if (addVariantBtn) {
                    addVariantBtn.disabled = false;
                    addVariantBtn.style.opacity = '1';
                    addVariantBtn.style.cursor = 'pointer';
                }

                // Disable Master Stock if Preorder
                if (masterStockInput) {
                    masterStockInput.disabled = isPreorder;
                    masterStockInput.style.background = isPreorder ? '#f3f4f6' : 'white';
                    if (isPreorder) {
                        masterStockInput.value = 0;
                    } else {
                        updateTotalStock();
                    }
                }
            }

            preOrderToggle.addEventListener('change', togglePreorderMode);
            
            // Initial check
            togglePreorderMode();

            // Toggle Collection dropdown based on Category selection
            const categorySelect = document.getElementById('categorySelect');
            const collectionContainer = document.getElementById('collectionContainer');
            const collectionSelect = document.getElementById('collectionSelect');
            
            function toggleCollection() {
                if (categorySelect && categorySelect.value !== '') {
                    if (collectionContainer) collectionContainer.style.display = 'block';
                } else {
                    if (collectionContainer) collectionContainer.style.display = 'none';
                    if (collectionSelect) collectionSelect.value = ''; // Reset when hidden
                }
            }
            if (categorySelect) categorySelect.addEventListener('change', toggleCollection);
            toggleCollection(); // Run on init
        })();
    </script>
@endsection
