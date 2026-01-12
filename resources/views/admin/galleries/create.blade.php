@extends('layouts.app')

@section('title', 'Add New Image')

@section('page-title', 'Add New Image')
@section('page-subtitle', 'Upload new image to gallery')

@section('content')
    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" class="form-input w-full rounded-md border-gray-300" value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" id="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept="image/*" required>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" class="form-input w-full rounded-md border-gray-300">{{ old('description') }}</textarea>
                </div>

                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_highlight" id="is_highlight" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" value="1" {{ old('is_highlight') ? 'checked' : '' }}>
                        <label for="is_highlight" class="ml-2 block text-sm text-gray-900">
                            Highlight on Homepage
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-6">If checked, this image will appear on the landing page carousel/grid.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </div>
            </form>
        </div>
    </div>
@endsection
