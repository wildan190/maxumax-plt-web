@extends('layouts.app')

@section('page-title', 'Products')
@section('page-subtitle', 'Manage and organize all your preorder products.')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2">
                <i data-feather="plus" class="w-4 h-4"></i>
                Add Product
            </a>
            <a href="{{ route('admin.products.reorder') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 gap-2">
                <i data-feather="sliders" class="w-4 h-4"></i>
                Reorder Products
            </a>
            <div class="h-8 w-px bg-slate-200 mx-1 hidden md:block"></div>
            <a href="{{ route('admin.products.template') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all gap-2">
                <i data-feather="download" class="w-4 h-4"></i>
                Template
            </a>
            <button type="button" onclick="document.getElementById('import-form').classList.toggle('hidden')" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all gap-2">
                <i data-feather="upload" class="w-4 h-4"></i>
                Import
            </button>
            <a href="{{ route('admin.products.export') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all gap-2">
                <i data-feather="file-text" class="w-4 h-4"></i>
                Export
            </a>
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="relative w-full lg:w-80 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-feather="search" class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
            </div>
            <input type="text" name="search" placeholder="Search by name..." value="{{ request('search') }}"
                class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
        </form>
    </div>

    <!-- Import Form (Toggleable) -->
    <div id="import-form" class="hidden animate-in fade-in slide-in-from-top-4 duration-300">
        <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-3xl">
            <h3 class="text-sm font-bold text-indigo-900 mb-2">Import products from CSV / Excel</h3>
            <p class="text-xs text-indigo-700/70 mb-6 leading-relaxed">Download the template above, fill in the data, save as CSV (UTF-8). If using Excel, save as "CSV UTF-8 (Comma delimited)". Then upload the file here.</p>
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-start md:items-center gap-4">
                @csrf
                <input type="file" name="file" accept=".csv,.txt" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all cursor-pointer">
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white text-xs font-bold rounded-full hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">Start Import</button>
                    <button type="button" onclick="document.getElementById('import-form').classList.add('hidden')" class="px-6 py-2 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-full hover:bg-slate-50 transition-all">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">{{ $p->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-slate-400 mr-0.5">RM</span>
                                <span class="font-black text-slate-900">{{ number_format($p->price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-bold text-xs {{ $p->stock < 10 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $p->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-100 bg-emerald-50 text-emerald-600">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border border-slate-100 bg-slate-50 text-slate-400">
                                        <span class="w-1 h-1 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <i data-feather="edit-3" class="w-4 h-4"></i>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium italic">
                                No product data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
