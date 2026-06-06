@extends('layouts.app')

@section('page-title', 'Bulk Upload Images')

@section('content')

    {{-- Subtitle --}}
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <p style="color: #6b7280; margin: 0; font-size: 0.95rem;">
            Upload multiple images to be displayed on gallery or landing page
        </p>
        <button type="button" id="addRowBtn" style="
            background: #4f46e5;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        ">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i>
            Add More Row
        </button>
    </div>

    @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div id="galleryContainer" style="display: flex; flex-direction: column; gap: 1.5rem;">
            {{-- Initial Row --}}
            <div class="gallery-row" style="
                background: white;
                border-radius: 0.75rem;
                border: 1px solid #e5e7eb;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                padding: 2rem;
                position: relative;
            ">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div>
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Title</label>
                            <input type="text" name="items[0][title]" required style="width: 100%; padding: 0.6rem 0.8rem; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.9rem;">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Description (Optional)</label>
                            <textarea name="items[0][description]" rows="2" style="width: 100%; padding: 0.6rem 0.8rem; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.9rem;"></textarea>
                        </div>

                        <div style="margin-bottom: 0;">
                            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                <input type="checkbox" name="items[0][is_highlight]" value="1">
                                <span style="font-size: 0.875rem; color: #374151; font-weight: 500;">Highlight on Homepage</span>
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0; font-size: 0.875rem;">Image File</label>
                        <div class="image-preview-container" style="
                            width: 100%;
                            height: 180px;
                            border: 2px dashed #e5e7eb;
                            border-radius: 0.75rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            position: relative;
                            overflow: hidden;
                            background: #f9fafb;
                        ">
                            <div class="preview-placeholder" style="text-align: center; color: #9ca3af;">
                                <i data-feather="image" style="width: 32px; height: 32px; margin-bottom: 0.5rem;"></i>
                                <p style="font-size: 0.75rem; font-weight: 500;">Click to upload</p>
                            </div>
                            <img class="image-preview" src="#" style="display: none; width: 100%; height: 100%; object-fit: contain;">
                            <input type="file" name="items[0][image]" accept="image/*" required class="image-input" style="
                                position: absolute;
                                inset: 0;
                                opacity: 0;
                                cursor: pointer;
                            ">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
            <a href="{{ route('admin.galleries.index') }}" style="
                padding: 0.7rem 1.6rem;
                border-radius: 0.5rem;
                background: #f3f4f6;
                color: #111827;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.9rem;
            ">
                Cancel
            </a>

            <button type="submit" style="
                padding: 0.7rem 2rem;
                border-radius: 0.5rem;
                background: #000;
                color: #fff;
                border: none;
                font-weight: 700;
                cursor: pointer;
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            ">
                Save All Images
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;
            const container = document.getElementById('galleryContainer');
            const addBtn = document.getElementById('addRowBtn');

            function initRowActions(row) {
                const input = row.querySelector('.image-input');
                const preview = row.querySelector('.image-preview');
                const placeholder = row.querySelector('.preview-placeholder');

                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                            placeholder.style.display = 'none';
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // Init first row
            initRowActions(container.querySelector('.gallery-row'));

            addBtn.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'gallery-row';
                newRow.style.cssText = `
                    background: white;
                    border-radius: 0.75rem;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                    padding: 2rem;
                    position: relative;
                    animation: slideIn 0.3s ease-out;
                `;
                
                newRow.innerHTML = `
                    <button type="button" class="remove-row" style="
                        position: absolute;
                        top: 1rem;
                        right: 1rem;
                        background: #fee2e2;
                        color: #ef4444;
                        border: none;
                        width: 2rem;
                        height: 2rem;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        z-index: 10;
                    ">
                        <i data-feather="x" style="width: 14px; height: 14px;"></i>
                    </button>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Title</label>
                                <input type="text" name="items[\${rowCount}][title]" required style="width: 100%; padding: 0.6rem 0.8rem; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.9rem;">
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem; font-size: 0.875rem;">Description (Optional)</label>
                                <textarea name="items[\${rowCount}][description]" rows="2" style="width: 100%; padding: 0.6rem 0.8rem; border-radius: 0.5rem; border: 1px solid #d1d5db; font-size: 0.9rem;"></textarea>
                            </div>

                            <div style="margin-bottom: 0;">
                                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                                    <input type="checkbox" name="items[\${rowCount}][is_highlight]" value="1">
                                    <span style="font-size: 0.875rem; color: #374151; font-weight: 500;">Highlight on Homepage</span>
                                </label>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0; font-size: 0.875rem;">Image File</label>
                            <div class="image-preview-container" style="
                                width: 100%;
                                height: 180px;
                                border: 2px dashed #e5e7eb;
                                border-radius: 0.75rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                position: relative;
                                overflow: hidden;
                                background: #f9fafb;
                            ">
                                <div class="preview-placeholder" style="text-align: center; color: #9ca3af;">
                                    <i data-feather="image" style="width: 32px; height: 32px; margin-bottom: 0.5rem;"></i>
                                    <p style="font-size: 0.75rem; font-weight: 500;">Click to upload</p>
                                </div>
                                <img class="image-preview" src="#" style="display: none; width: 100%; height: 100%; object-fit: contain;">
                                <input type="file" name="items[\${rowCount}][image]" accept="image/*" required class="image-input" style="
                                    position: absolute;
                                    inset: 0;
                                    opacity: 0;
                                    cursor: pointer;
                                ">
                            </div>
                        </div>
                    </div>
                `;

                container.appendChild(newRow);
                initRowActions(newRow);
                
                newRow.querySelector('.remove-row').addEventListener('click', function() {
                    newRow.style.animation = 'slideOut 0.2s ease-in forwards';
                    setTimeout(() => newRow.remove(), 200);
                });

                if (typeof feather !== 'undefined') feather.replace();
                rowCount++;
            });
        });
    </script>

    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
    </style>

@endsection
