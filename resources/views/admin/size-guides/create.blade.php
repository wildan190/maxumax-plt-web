@extends('layouts.app')

@section('page-title', 'Bulk Upload Size Guides (PDF)')

@section('content')
    <div style="background: white; border-radius: 1.25rem; border: 1px solid #e5e7eb; padding: 2.5rem; max-width: 800px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: 800; color: #111827; text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 0.5rem;">Upload PDF Files</h2>
            <p style="color: #6b7280; font-size: 0.875rem;">Select one or more PDF files. The name of the file will be used as the title of the size guide.</p>
        </div>

        @if($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem;">
                <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem; font-weight: 500;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.size-guides.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 2.5rem;">
                <div id="dropzone" style="border: 2px dashed #e5e7eb; border-radius: 1rem; padding: 3rem 2rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #f9fafb;">
                    <div style="width: 4rem; height: 4rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                        <i data-feather="upload-cloud" style="width: 24px; height: 24px; color: #4f46e5;"></i>
                    </div>
                    <p style="font-size: 1rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem;">Click or drag PDF files here</p>
                    <p style="font-size: 0.8125rem; color: #6b7280;">Support multiple PDF uploads (Max 10MB per file)</p>
                    <input type="file" name="files[]" id="fileInput" multiple accept="application/pdf" style="display: none;">
                </div>
                
                <div id="fileList" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                    <!-- Selected files will be listed here -->
                </div>
            </div>

            <div style="display: flex; gap: 1rem; border-top: 1px solid #f3f4f6; padding-top: 2rem;">
                <button type="submit" id="submitBtn" disabled style="background: #4f46e5; color: #fff; padding: 0.875rem 2.5rem; border-radius: 0.75rem; font-weight: 800; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s; opacity: 0.5;">
                    Upload All Guides
                </button>
                <a href="{{ route('admin.size-guides.index') }}" style="display: inline-block; padding: 0.875rem 2rem; border-radius: 0.75rem; font-weight: 700; color: #4b5563; text-decoration: none; background: #f3f4f6; text-transform: uppercase; letter-spacing: 0.05em; transition: all 0.2s;">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('fileList');
            const submitBtn = document.getElementById('submitBtn');

            dropzone.addEventListener('click', () => fileInput.click());

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.style.borderColor = '#4f46e5';
                dropzone.style.background = '#eef2ff';
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.style.borderColor = '#e5e7eb';
                dropzone.style.background = '#f9fafb';
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.style.borderColor = '#e5e7eb';
                dropzone.style.background = '#f9fafb';
                fileInput.files = e.dataTransfer.files;
                updateFileList();
            });

            fileInput.addEventListener('change', updateFileList);

            function updateFileList() {
                fileList.innerHTML = '';
                const files = Array.from(fileInput.files);
                
                if (files.length > 0) {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    
                    files.forEach((file, index) => {
                        const row = document.createElement('div');
                        row.style.cssText = 'display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem;';
                        row.innerHTML = `
                            <i data-feather="file-text" style="width: 16px; height: 16px; color: #64748b;"></i>
                            <span style="font-size: 0.875rem; font-weight: 600; color: #1e293b; flex: 1;">${file.name}</span>
                            <span style="font-size: 0.75rem; color: #94a3b8;">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                        `;
                        fileList.appendChild(row);
                    });
                    
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                } else {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                }
            }
        });
    </script>
@endsection
