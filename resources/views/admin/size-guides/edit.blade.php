@extends('layouts.app')

@section('page-title', 'Edit Size Guide')

@section('content')
    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; max-width: 1000px;">
        <form action="{{ route('admin.size-guides.update', $sizeGuide) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Name</label>
                <input type="text" name="name" value="{{ old('name', $sizeGuide->name) }}" required
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Image Chart (.webp or .png)</label>
                @if($sizeGuide->image_path)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $sizeGuide->image_path) }}" style="max-width: 200px; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                    </div>
                @endif
                <input type="file" name="image" accept=".webp,.png"
                       style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $sizeGuide->is_active ? 'checked' : '' }}
                           style="width: 1.125rem; height: 1.125rem; border-radius: 0.375rem;">
                    <span style="font-weight: 600; color: #374151;">Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; border-top: 1px solid #e5e7eb; pt: 1.5rem;">
                <button type="submit" style="background: #000; color: #fff; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 700; border: none; cursor: pointer;">
                    Update Size Guide
                </button>
                <a href="{{ route('admin.size-guides.index') }}" style="display: inline-block; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 600; color: #4b5563; text-decoration: none; background: #f3f4f6;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
