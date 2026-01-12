@extends('layouts.app')

@section('page-title', 'Edit Image')

@section('content')

    {{-- Subtitle --}}
    <div style="margin-bottom: 2rem;">
        <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">
            Update gallery image details
        </p>
    </div>

    {{-- Form Card --}}
    <div style="
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 2.5rem;
    ">

        <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div style="margin-bottom: 2rem;">
                <label for="title" style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                    Title
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $gallery->title) }}"
                    required
                    style="
                        width: 100%;
                        padding: 0.75rem 0.85rem;
                        border-radius: 0.5rem;
                        border: 1px solid #d1d5db;
                        font-size: 0.95rem;
                    "
                >
                @error('title')
                    <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.5rem;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Current Image --}}
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.75rem;">
                    Current Image
                </label>

                <div style="margin-bottom: 1rem;">
                    <img
                        src="{{ Storage::url($gallery->image_path) }}"
                        alt="{{ $gallery->title }}"
                        style="
                            max-width: 100%;
                            height: 260px;
                            object-fit: cover;
                            border-radius: 0.5rem;
                            border: 1px solid #e5e7eb;
                        "
                    >
                </div>

                <label for="image" style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                    Change Image <span style="color: #9ca3af; font-weight: 400;">(Optional)</span>
                </label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    accept="image/*"
                    style="
                        width: 100%;
                        padding: 0.6rem;
                        border-radius: 0.5rem;
                        border: 1px solid #d1d5db;
                        background: #f9fafb;
                    "
                >

                @error('image')
                    <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.5rem;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Description --}}
            <div style="margin-bottom: 2rem;">
                <label for="description" style="display: block; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">
                    Description <span style="color: #9ca3af; font-weight: 400;">(Optional)</span>
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    style="
                        width: 100%;
                        padding: 0.75rem 0.85rem;
                        border-radius: 0.5rem;
                        border: 1px solid #d1d5db;
                        font-size: 0.95rem;
                    "
                >{{ old('description', $gallery->description) }}</textarea>
            </div>

            {{-- Highlight --}}
            <div style="margin-bottom: 3rem;">
                <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: pointer;">
                    <input
                        type="checkbox"
                        name="is_highlight"
                        value="1"
                        {{ old('is_highlight', $gallery->is_highlight) ? 'checked' : '' }}
                        style="margin-top: 0.35rem;"
                    >
                    <div>
                        <div style="font-weight: 600; color: #111827;">
                            Highlight on Homepage
                        </div>
                        <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem;">
                            If enabled, this image will appear on landing page sections.
                        </div>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                <a href="{{ route('admin.galleries.index') }}"
                   style="
                        padding: 0.7rem 1.6rem;
                        border-radius: 0.5rem;
                        background: #f3f4f6;
                        color: #111827;
                        text-decoration: none;
                        font-weight: 500;
                   ">
                    Cancel
                </a>

                <button type="submit"
                        style="
                            padding: 0.7rem 1.6rem;
                            border-radius: 0.5rem;
                            background: #000;
                            color: #fff;
                            border: none;
                            font-weight: 600;
                            cursor: pointer;
                        ">
                    Update Image
                </button>
            </div>

        </form>
    </div>

@endsection
