@extends('layouts.app')

@section('page-title', 'User Management')
@section('page-subtitle', 'Manage application users, roles, and access permissions.')

@section('content')
<div class="space-y-6">
    {{-- Header & Search --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="w-full lg:w-auto overflow-x-auto">
            <div class="flex items-center gap-2 pb-2 lg:pb-0">
                <a href="{{ route('admin.users.index') }}" 
                    class="px-4 py-2 text-sm font-bold rounded-lg whitespace-nowrap transition-all {{ ($role ?? 'all') === 'all' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    All Users <span class="ml-1 opacity-60 text-xs">{{ $counts['all'] }}</span>
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
                    class="px-4 py-2 text-sm font-bold rounded-lg whitespace-nowrap transition-all {{ $role === 'admin' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    Administrators <span class="ml-1 opacity-60 text-xs">{{ $counts['admin'] }}</span>
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" 
                    class="px-4 py-2 text-sm font-bold rounded-lg whitespace-nowrap transition-all {{ $role === 'staff' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    Staff <span class="ml-1 opacity-60 text-xs">{{ $counts['staff'] }}</span>
                </a>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row w-full lg:w-auto gap-3">
            <form method="GET" action="{{ route('admin.users.index') }}" class="relative group flex-1 sm:w-80">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
                <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
            </form>

            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2">
                <i data-feather="plus" class="w-4 h-4"></i>
                Add User
            </a>
        </div>
    </div>

    {{-- User Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">User Info</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900">{{ $user->name }}</span>
                                        <span class="text-xs text-slate-500">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $roleBadge = match($user->role) {
                                        'admin' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'staff' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $roleBadge }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <i data-feather="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="js-delete" data-title="Delete user?" data-text="This user will be permanently removed.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 font-medium italic">
                                No users found match your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('form.js-delete').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: this.dataset.title || 'Delete item?',
                    text: this.dataset.text || 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    borderRadius: '1.5rem'
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
