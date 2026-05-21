@extends('layouts.app')

@section('page-title', 'Landing Page Management')
@section('page-subtitle', 'Configure hero slides, shop categories, collections, and showcase projects.')

@section('content')
<div x-data="{ activeTab: 'hero' }" class="space-y-6">
    <!-- Header with Global Save -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/20">
                <i data-feather="layout" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight">Landing Page</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Homepage Content Configuration</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="submit" form="landing-page-form" class="flex-1 md:flex-none inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white text-sm font-black rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 gap-2 uppercase tracking-widest">
                <i data-feather="save" class="w-4 h-4"></i>
                Save All Changes
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl font-bold text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <i data-feather="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="sticky top-24 bg-white rounded-3xl border border-slate-200 p-3 shadow-sm">
                <nav class="space-y-1">
                    <button @click="activeTab = 'hero'" 
                        :class="activeTab === 'hero' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group">
                        <i data-feather="monitor" class="w-4 h-4" :class="activeTab === 'hero' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'"></i>
                        <span class="text-sm font-bold uppercase tracking-widest">Hero Slides</span>
                    </button>
                    <button @click="activeTab = 'shop'" 
                        :class="activeTab === 'shop' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group">
                        <i data-feather="shopping-bag" class="w-4 h-4" :class="activeTab === 'shop' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'"></i>
                        <span class="text-sm font-bold uppercase tracking-widest">Shop Categories</span>
                    </button>
                    <button @click="activeTab = 'featured'" 
                        :class="activeTab === 'featured' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group">
                        <i data-feather="star" class="w-4 h-4" :class="activeTab === 'featured' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'"></i>
                        <span class="text-sm font-bold uppercase tracking-widest">Featured</span>
                    </button>
                    <button @click="activeTab = 'projects'" 
                        :class="activeTab === 'projects' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group">
                        <i data-feather="briefcase" class="w-4 h-4" :class="activeTab === 'projects' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'"></i>
                        <span class="text-sm font-bold uppercase tracking-widest">Projects</span>
                    </button>
                </nav>

                <div class="mt-8 pt-6 border-t border-slate-100 px-2 space-y-4">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 px-2">Danger Zone</h4>
                    <form action="{{ route('admin.landing-page.reset-hero') }}" method="POST" onsubmit="return confirm('Reset hero slides to default?')">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors text-[10px] font-black uppercase tracking-widest">
                            <i data-feather="refresh-cw" class="w-3 h-3"></i>
                            Reset Hero
                        </button>
                    </form>
                    <form action="{{ route('admin.landing-page.reset-shop') }}" method="POST" onsubmit="return confirm('Reset shop items to default?')">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors text-[10px] font-black uppercase tracking-widest">
                            <i data-feather="refresh-cw" class="w-3 h-3"></i>
                            Reset Shop
                        </button>
                    </form>
                    <form action="{{ route('admin.landing-page.reset-featured') }}" method="POST" onsubmit="return confirm('Reset featured items to default?')">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors text-[10px] font-black uppercase tracking-widest">
                            <i data-feather="refresh-cw" class="w-3 h-3"></i>
                            Reset Featured
                        </button>
                    </form>
                    <form action="{{ route('admin.landing-page.reset-projects') }}" method="POST" onsubmit="return confirm('Reset project items to default?')">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors text-[10px] font-black uppercase tracking-widest">
                            <i data-feather="refresh-cw" class="w-3 h-3"></i>
                            Reset Projects
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Form Area -->
        <div class="flex-1">
            <form id="landing-page-form" action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- 1. HERO SLIDES TAB -->
                <div x-show="activeTab === 'hero'" class="animate-in fade-in slide-in-from-right-4 duration-300">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">Hero Slides</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Main Banner Carousel</p>
                            </div>
                            <button type="button" onclick="addHeroRow()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-black rounded-xl hover:bg-slate-50 transition-all uppercase tracking-widest shadow-sm">
                                <i data-feather="plus" class="w-3 h-3"></i>
                                Add Slide
                            </button>
                        </div>
                        <div class="p-8" id="hero-container">
                            @foreach ($heroSlides as $index => $slide)
                                @include('admin.landing-page.partials.hero-row', ['index' => $index, 'slide' => $slide])
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. SHOP CATEGORIES TAB -->
                <div x-show="activeTab === 'shop'" class="animate-in fade-in slide-in-from-right-4 duration-300" style="display: none;">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">Shop Categories</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Shop by Sport section</p>
                            </div>
                            <button type="button" onclick="addShopRow()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-black rounded-xl hover:bg-slate-50 transition-all uppercase tracking-widest shadow-sm">
                                <i data-feather="plus" class="w-3 h-3"></i>
                                Add Category
                            </button>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6" id="shop-container">
                            @foreach ($shopItems as $index => $item)
                                @include('admin.landing-page.partials.shop-row', ['index' => $index, 'item' => $item])
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 3. FEATURED TAB -->
                <div x-show="activeTab === 'featured'" class="animate-in fade-in slide-in-from-right-4 duration-300" style="display: none;">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">Featured Collections</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Special collections highlight</p>
                            </div>
                            <button type="button" onclick="addFeaturedRow()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-black rounded-xl hover:bg-slate-50 transition-all uppercase tracking-widest shadow-sm">
                                <i data-feather="plus" class="w-3 h-3"></i>
                                Add Item
                            </button>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6" id="featured-container">
                            @foreach ($featuredItems as $index => $item)
                                @include('admin.landing-page.partials.featured-row', ['index' => $index, 'item' => $item])
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 4. PROJECTS TAB -->
                <div x-show="activeTab === 'projects'" class="animate-in fade-in slide-in-from-right-4 duration-300" style="display: none;">
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h2 class="text-lg font-black text-slate-900 uppercase tracking-tight">Projects Showcase</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Trusted project items</p>
                            </div>
                            <button type="button" onclick="addProjectRow()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-black rounded-xl hover:bg-slate-50 transition-all uppercase tracking-widest shadow-sm">
                                <i data-feather="plus" class="w-3 h-3"></i>
                                Add Project
                            </button>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6" id="projects-container">
                            @foreach ($projectItems as $index => $item)
                                @include('admin.landing-page.partials.project-row', ['index' => $index, 'item' => $item])
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        function addHeroRow() {
            const container = document.getElementById('hero-container');
            const index = container.querySelectorAll('[data-hero-row]').length;
            fetch(`{{ route('admin.landing-page.partial-hero') }}?index=${index}`)
                .then(res => res.text())
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
        }

        function removeHeroRow(btn) {
            if (confirm('Remove this slide?')) {
                btn.closest('[data-hero-row]').remove();
                reindexHeroRows();
            }
        }

        function reindexHeroRows() {
            const container = document.getElementById('hero-container');
            const rows = container.querySelectorAll('[data-hero-row]');
            rows.forEach((row, i) => {
                row.querySelector('.font-bold.text-sm').textContent = i + 1;
                // re-index inputs if necessary for validation
                row.querySelectorAll('input, textarea').forEach(input => {
                    input.name = input.name.replace(/hero\[\d+\]/, `hero[${i}]`);
                });
            });
        }

        function addShopRow() {
            const container = document.getElementById('shop-container');
            const index = container.querySelectorAll('[data-shop-row]').length;
            fetch(`{{ route('admin.landing-page.partial-shop') }}?index=${index}`)
                .then(res => res.text())
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
        }

        function removeShopRow(btn) {
            if (confirm('Remove this item?')) {
                btn.closest('[data-shop-row]').remove();
                reindexShopRows();
            }
        }

        function reindexShopRows() {
            const container = document.getElementById('shop-container');
            const rows = container.querySelectorAll('[data-shop-row]');
            rows.forEach((row, i) => {
                row.querySelector('.font-bold.text-xs').textContent = i + 1;
                row.querySelectorAll('input').forEach(input => {
                    input.name = input.name.replace(/shop\[\d+\]/, `shop[${i}]`);
                });
            });
        }

        function addFeaturedRow() {
            const container = document.getElementById('featured-container');
            const index = container.querySelectorAll('[data-featured-row]').length;
            fetch(`{{ route('admin.landing-page.partial-featured') }}?index=${index}`)
                .then(res => res.text())
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
        }

        function removeFeaturedRow(btn) {
            if (confirm('Remove this item?')) {
                btn.closest('[data-featured-row]').remove();
                reindexFeaturedRows();
            }
        }

        function reindexFeaturedRows() {
            const container = document.getElementById('featured-container');
            const rows = container.querySelectorAll('[data-featured-row]');
            rows.forEach((row, i) => {
                row.querySelector('.font-bold.text-xs').textContent = i + 1;
                row.querySelectorAll('input').forEach(input => {
                    input.name = input.name.replace(/featured\[\d+\]/, `featured[${i}]`);
                });
            });
        }

        function addProjectRow() {
            const container = document.getElementById('projects-container');
            const index = container.querySelectorAll('[data-project-row]').length;
            fetch(`{{ route('admin.landing-page.partial-project') }}?index=${index}`)
                .then(res => res.text())
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
        }

        function removeProjectRow(btn) {
            if (confirm('Remove this project?')) {
                btn.closest('[data-project-row]').remove();
                reindexProjectRows();
            }
        }

        function reindexProjectRows() {
            const container = document.getElementById('projects-container');
            const rows = container.querySelectorAll('[data-project-row]');
            rows.forEach((row, i) => {
                row.querySelector('.font-bold.text-xs').textContent = i + 1;
                row.querySelectorAll('input, textarea').forEach(input => {
                    input.name = input.name.replace(/projects\[\d+\]/, `projects[${i}]`);
                });
            });
        }
    </script>
@endpush
