@extends('layouts.app')

@section('page-title', 'Gallery')

@section('content')
    {{-- Header --}}
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">
                Manage and organize gallery images for landing page
            </p>
        </div>
        <a href="{{ route('admin.galleries.create') }}"
           style="display: inline-flex; align-items: center; gap: 0.5rem; background: #000; color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background 0.2s;">
            + Add Image
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Image</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Title</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Description</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($galleries as $g)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            {{-- Image --}}
                            <td style="padding: 1rem;">
                                <img src="{{ Storage::url($g->image_path) }}"
                                     alt="{{ $g->title }}"
                                     style="width: 64px; height: 64px; object-fit: cover; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                            </td>

                            {{-- Title --}}
                            <td style="padding: 1rem; color: #111827; font-weight: 500;">
                                {{ $g->title }}
                            </td>

                            {{-- Description --}}
                            <td style="padding: 1rem; color: #6b7280; font-size: 0.9rem;">
                                {{ Str::limit($g->description, 60) ?: '—' }}
                            </td>

                            {{-- Status --}}
                            <td style="padding: 1rem;">
                                <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                    @if($g->is_highlight)
                                        <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                                        <span style="color: #065f46; font-weight: 500; font-size: 0.85rem;">Highlight</span>
                                    @else
                                        <span style="width: 8px; height: 8px; background: #9ca3af; border-radius: 50%;"></span>
                                        <span style="color: #374151; font-weight: 500; font-size: 0.85rem;">Normal</span>
                                    @endif
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="{{ route('admin.galleries.edit', $g) }}"
                                       style="color: #000; text-decoration: none; font-weight: 500; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #f3f4f6; font-size: 0.875rem;">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.galleries.destroy', $g) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Delete this image?')"
                                                style="background: #fee2e2; color: #991b1b; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 500; cursor: pointer; font-size: 0.875rem;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 3rem; text-align: center; color: #6b7280;">
                                No images found.
                                <a href="{{ route('admin.galleries.create') }}" style="color: #000; font-weight: 600;">
                                    Add one
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($galleries->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
@endsection
