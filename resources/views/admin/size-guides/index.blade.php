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
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Slug</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.875rem; text-transform: uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guides as $g)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 1rem; color: #111827; font-weight: 500;">
                                {{ $g->name }}
                            </td>
                            <td style="padding: 1rem; color: #6b7280; font-size: 0.9rem;">
                                {{ $g->slug }}
                            </td>
                            <td style="padding: 1rem;">
                                @if($g->is_active)
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%;"></span>
                                        <span style="color: #065f46; font-weight: 500; font-size: 0.85rem;">Active</span>
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                        <span style="width: 8px; height: 8px; background: #9ca3af; border-radius: 50%;"></span>
                                        <span style="color: #374151; font-weight: 500; font-size: 0.85rem;">Inactive</span>
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.75rem;">
                                    <a href="{{ route('admin.size-guides.edit', $g) }}"
                                       style="color: #000; text-decoration: none; font-weight: 500; padding: 0.5rem 0.75rem; border-radius: 0.375rem; background: #f3f4f6; font-size: 0.875rem;">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.size-guides.destroy', $g) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this size guide?')"
                                                style="background: #fee2e2; color: #991b1b; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-weight: 500; cursor: pointer; font-size: 0.875rem;">
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
