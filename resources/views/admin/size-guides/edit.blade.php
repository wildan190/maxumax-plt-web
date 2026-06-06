@extends('layouts.app')

@section('page-title', 'Update Size Guide PDF')

@section('content')
    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; max-width: 800px;">
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.1rem; font-weight: 800; color: #111827; text-transform: uppercase;">Editing: {{ $sizeGuide->name }}</h2>
            <p style="color: #6b7280; font-size: 0.85rem;">You can replace the PDF file below. The name will be updated if you upload a new file.</p>
        </div>

        <form action="{{ route('admin.size-guides.update', $sizeGuide->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.75rem;">PDF File</label>
                @if($sizeGuide->image_path)
                    @php
                        $isPdf = str_ends_with(strtolower($sizeGuide->image_path), '.pdf');
                    @endphp
                    @if($isPdf)
                        <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem;">
                            <div style="width: 2.5rem; height: 2.5rem; background: #fee2e2; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #ef4444;">
                                <i data-feather="file-text" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $sizeGuide->name }}.pdf</p>
                                <p style="font-size: 0.75rem; color: #64748b; margin: 0;">Current active document</p>
                            </div>
                            <a href="{{ asset('storage/' . $sizeGuide->image_path) }}" target="_blank" style="font-size: 0.75rem; color: #4f46e5; font-weight: 800; text-transform: uppercase; text-decoration: none; padding: 0.5rem 1rem; background: white; border: 1px solid #e2e8f0; border-radius: 0.5rem;">View</a>
                        </div>
                    @else
                        <div style="margin-bottom: 1.5rem;">
                            <img src="{{ asset('storage/' . $sizeGuide->image_path) }}" style="max-width: 200px; border-radius: 0.75rem; border: 1px solid #e5e7eb;">
                        </div>
                    @endif
                @endif
                
                <div style="position: relative;">
                    <input type="file" name="file" accept="application/pdf"
                           style="width: 100%; padding: 0.75rem; border: 2px dashed #d1d5db; border-radius: 0.75rem; outline: none; background: #f9fafb;">
                    <p style="margin-top: 0.5rem; font-size: 0.75rem; color: #9ca3af;">Leave empty to keep current file. Only PDF supported.</p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; border-top: 1px solid #f3f4f6; padding-top: 2rem;">
                <button type="submit" style="background: #000; color: #fff; padding: 0.875rem 2.5rem; border-radius: 0.75rem; font-weight: 800; border: none; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em;">
                    Update Guide
                </button>
                <a href="{{ route('admin.size-guides.index') }}" style="display: inline-block; padding: 0.875rem 2rem; border-radius: 0.75rem; font-weight: 700; color: #4b5563; text-decoration: none; background: #f3f4f6; text-transform: uppercase; letter-spacing: 0.05em;">
                    Back
                </a>
            </div>
        </form>
    </div>
@endsection
