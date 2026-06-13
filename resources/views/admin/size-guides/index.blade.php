@extends('layouts.app')

@section('page-title', 'Size Guides')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.95rem;">
                Manage size charts for your products
            </p>
        </div>
        <a href="{{ route('admin.size-guides.create') }}"
           style="display: inline-flex; align-items: center; gap: 0.5rem; background: #000; color: #fff; padding: 0.625rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: background 0.2s;">
            + Add Size Guide
        </a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Name</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guides as $g)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 1rem; color: #111827; font-weight: 700;">
                                {{ $g->name }}
                            </td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.75rem; align-items: center;">
                                    <a href="{{ asset('storage/' . $g->image_path) }}" target="_blank"
                                       style="color: #4f46e5; text-decoration: none; font-weight: 700; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #eef2ff; font-size: 0.75rem; text-transform: uppercase;">
                                        View PDF
                                    </a>
                                    <a href="{{ route('admin.size-guides.edit', $g->slug) }}"
                                       style="color: #000; text-decoration: none; font-weight: 600; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #f3f4f6; font-size: 0.75rem; text-transform: uppercase;">
                                        Replace
                                    </a>
                                    <form action="{{ route('admin.size-guides.destroy', $g->slug) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this size guide?')"
                                                style="background: #fee2e2; color: #991b1b; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 0.75rem; text-transform: uppercase;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 3rem; text-align: center; color: #6b7280;">
                                No size guides found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
