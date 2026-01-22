@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')
    <div style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between; align-items: flex-start;">
        <div>
            <p style="color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.95rem;">Manage application users and their roles</p>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 0.5rem;">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <input type="text" name="search" placeholder="Search by name/email..." value="{{ request('search') }}" 
                    style="padding: 0.625rem 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; font-size: 0.95rem; min-width: 240px;" />
                <button type="submit" style="background: #000; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">Search</button>
            </form>
            <a href="{{ route('admin.users.create') }}" style="background: #6366f1; color: white; padding: 0.625rem 1.5rem; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                <i data-feather="plus" style="width: 18px; height: 18px;"></i> Add User
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div style="margin-bottom: 1.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap; border-bottom: 1px solid #e5e7eb;">
            <a href="{{ route('admin.users.index') }}" style="padding: 1rem 1.5rem; color: {{ !$role || $role === 'all' ? '#111827' : '#6b7280' }}; font-weight: 600; font-size: 0.875rem; text-decoration: none; border-bottom: 2px solid {{ !$role || $role === 'all' ? '#2563eb' : 'transparent' }}; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                Semua User <span style="background: {{ !$role || $role === 'all' ? '#eff6ff' : '#f3f4f6' }}; color: {{ !$role || $role === 'all' ? '#2563eb' : '#6b7280' }}; padding: 0.125rem 0.5rem; border-radius: 1rem; font-size: 0.75rem;">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" style="padding: 1rem 1.5rem; color: {{ $role === 'admin' ? '#111827' : '#6b7280' }}; font-weight: 600; font-size: 0.875rem; text-decoration: none; border-bottom: 2px solid {{ $role === 'admin' ? '#2563eb' : 'transparent' }}; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                Administrators <span style="background: {{ $role === 'admin' ? '#eff6ff' : '#f3f4f6' }}; color: {{ $role === 'admin' ? '#2563eb' : '#6b7280' }}; padding: 0.125rem 0.5rem; border-radius: 1rem; font-size: 0.75rem;">{{ $counts['admin'] }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" style="padding: 1rem 1.5rem; color: {{ $role === 'staff' ? '#111827' : '#6b7280' }}; font-weight: 600; font-size: 0.875rem; text-decoration: none; border-bottom: 2px solid {{ $role === 'staff' ? '#2563eb' : 'transparent' }}; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                Staff <span style="background: {{ $role === 'staff' ? '#eff6ff' : '#f3f4f6' }}; color: {{ $role === 'staff' ? '#2563eb' : '#6b7280' }}; padding: 0.125rem 0.5rem; border-radius: 1rem; font-size: 0.75rem;">{{ $counts['staff'] }}</span>
            </a>
    </div>

    @if (session('status'))
        <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
            <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
            <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
            {{ session('error') }}
        </div>
    @endif

    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; width: 100%;">
        <div style="overflow-x: auto; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">User Info</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Role</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Created At</th>
                        <th style="padding: 1rem; text-align: right; font-weight: 600; color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.2s;">
                            <td style="padding: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #f3f4f6; color: #4b5563; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="color: #111827; font-weight: 600;">{{ $user->name }}</div>
                                        <div style="color: #6b7280; font-size: 0.85rem;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1rem;">
                                @php
                                    $roleColors = [
                                        'admin' => ['#fee2e2', '#991b1b'],
                                        'staff' => ['#e0e7ff', '#3730a3'],
                                        'user' => ['#f3f4f6', '#4b5563']
                                    ];
                                    [$bg, $fg] = $roleColors[$user->role] ?? $roleColors['user'];
                                @endphp
                                <span style="background: {{ $bg }}; color: {{ $fg }}; padding: 0.25rem 0.625rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-flex; align-items: center;">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td style="padding: 1rem; color: #6b7280; font-size: 0.875rem;">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                            </td>
                            <td style="padding: 1rem; text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="{{ route('admin.users.edit', $user) }}" style="background: #f3f4f6; color: #111827; padding: 0.5rem; border-radius: 0.375rem; display: flex; transition: all 0.2s;" title="Edit User">
                                        <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" class="js-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: #fee2e2; color: #991b1b; padding: 0.5rem; border: none; border-radius: 0.375rem; cursor: pointer; display: flex; transition: all 0.2s;" title="Delete User">
                                                <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 3rem; text-align: center; color: #6b7280;">
                                <div style="margin-bottom: 1rem;">
                                    <i data-feather="users" style="width: 48px; height: 48px; opacity: 0.2;"></i>
                                </div>
                                No users found match your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form.js-delete').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Delete user?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#000',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel',
                        borderRadius: '0.75rem'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
