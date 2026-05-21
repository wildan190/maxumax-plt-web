@extends('layouts.app')

@section('page-title', 'Landing page')

@section('content')
    @if (session('success'))
        <div style="margin-bottom: 1.25rem; padding: 0.85rem 1rem; border-radius: 0.5rem; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom: 2rem;">
        <p style="color: #6b7280; margin: 0; font-size: 0.95rem;">
            Kelola hero, Shop by sport, dan Featured collections untuk halaman utama. Jika sebuah bagian tidak punya data kustom, situs memakai tampilan bawaan.
            Semua unggahan gambar landing page wajib format <strong>.webp</strong> dan maksimal <strong>2 MB</strong> per file.
        </p>
    </div>

    <form action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data" id="landing-page-form">
        @csrf
        @method('PUT')

        <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; margin-bottom: 2rem;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Hero (desktop slider)</h2>
                <button type="button" onclick="addHeroRow()" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: #f9fafb; font-weight: 600; cursor: pointer;">Tambah slide</button>
            </div>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Maksimal 3 tombol per slide. URL tombol boleh tautan internal atau eksternal.</p>

            <div id="hero-rows">
                @forelse ($heroSlides as $slide)
                    @include('admin.landing-page.partials.hero-row', ['index' => $loop->index, 'slide' => $slide])
                @empty
                    @include('admin.landing-page.partials.hero-row', ['index' => 0, 'slide' => null])
                @endforelse
            </div>
        </div>

        <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; margin-bottom: 2rem;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Shop by sport</h2>
                <button type="button" onclick="addShopRow()" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: #f9fafb; font-weight: 600; cursor: pointer;">Tambah item</button>
            </div>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Field <em>sport (query)</em> harus cocok dengan nilai query <code>sport</code> di katalog (contoh: <code>Football Series</code>).</p>

            <div id="shop-rows">
                @forelse ($shopItems as $item)
                    @include('admin.landing-page.partials.shop-row', ['index' => $loop->index, 'item' => $item])
                @empty
                    @include('admin.landing-page.partials.shop-row', ['index' => 0, 'item' => null])
                @endforelse
            </div>
        </div>

        <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; margin-bottom: 2rem;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Featured collections</h2>
                <button type="button" onclick="addFeaturedRow()" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: #f9fafb; font-weight: 600; cursor: pointer;">Tambah item</button>
            </div>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Field <em>filter (query)</em> cocok dengan query <code>filter</code> di katalog (contoh: <code>Sale / Clearance</code>).</p>

            <div id="featured-rows">
                @forelse ($featuredItems as $item)
                    @include('admin.landing-page.partials.featured-row', ['index' => $loop->index, 'item' => $item])
                @empty
                    @include('admin.landing-page.partials.featured-row', ['index' => 0, 'item' => null])
                @endforelse
            </div>
        </div>

        <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; margin-bottom: 2rem;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0; font-size: 1.15rem; font-weight: 700;">Trusted projects</h2>
                <button type="button" onclick="addProjectRow()" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background: #f9fafb; font-weight: 600; cursor: pointer;">Tambah proyek</button>
            </div>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Section ini menampilkan tim, pemerintah, atau korporat yang mempercayai MAXUMAX.</p>

            <div id="project-rows">
                @forelse ($projectItems as $item)
                    @include('admin.landing-page.partials.project-row', ['index' => $loop->index, 'item' => $item])
                @empty
                    @include('admin.landing-page.partials.project-row', ['index' => 0, 'item' => null])
                @endforelse
            </div>
        </div>

        <button type="submit" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem; border: none; background: #4f46e5; color: white; font-weight: 700; cursor: pointer;">Simpan perubahan</button>
    </form>

    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; flex-wrap: wrap; gap: 0.75rem;">
        <form action="{{ route('admin.landing-page.reset-hero') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" onclick="return confirm('Hapus semua slide hero kustom dan kembali ke default?');"
                style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; font-weight: 600; cursor: pointer;">Reset hero ke default</button>
        </form>
        <form action="{{ route('admin.landing-page.reset-shop') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" onclick="return confirm('Hapus semua item Shop by sport kustom?');"
                style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; font-weight: 600; cursor: pointer;">Reset shop by sport</button>
        </form>
        <form action="{{ route('admin.landing-page.reset-featured') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" onclick="return confirm('Hapus semua item Featured collections kustom?');"
                style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; font-weight: 600; cursor: pointer;">Reset featured</button>
        </form>
        <form action="{{ route('admin.landing-page.reset-projects') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" onclick="return confirm('Hapus semua item Trusted projects kustom?');"
                style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; font-weight: 600; cursor: pointer;">Reset projects</button>
        </form>
    </div>

    <template id="tpl-hero-row">
        {!! view('admin.landing-page.partials.hero-row', ['index' => '__IDX__', 'slide' => null])->render() !!}
    </template>
    <template id="tpl-shop-row">
        {!! view('admin.landing-page.partials.shop-row', ['index' => '__IDX__', 'item' => null])->render() !!}
    </template>
    <template id="tpl-featured-row">
        {!! view('admin.landing-page.partials.featured-row', ['index' => '__IDX__', 'item' => null])->render() !!}
    </template>
    <template id="tpl-project-row">
        {!! view('admin.landing-page.partials.project-row', ['index' => '__IDX__', 'item' => null])->render() !!}
    </template>

    <script>
        function reindexHeroRows() {
            document.querySelectorAll('#hero-rows [data-hero-row]').forEach(function (row, i) {
                row.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace(/^hero\[\d+\]/, 'hero[' + i + ']');
                });
            });
        }

        function reindexShopRows() {
            document.querySelectorAll('#shop-rows [data-shop-row]').forEach(function (row, i) {
                row.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace(/^shop\[\d+\]/, 'shop[' + i + ']');
                });
            });
        }

        function reindexFeaturedRows() {
            document.querySelectorAll('#featured-rows [data-featured-row]').forEach(function (row, i) {
                row.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace(/^featured\[\d+\]/, 'featured[' + i + ']');
                });
            });
        }

        function reindexProjectRows() {
            document.querySelectorAll('#project-rows [data-project-row]').forEach(function (row, i) {
                row.querySelectorAll('[name]').forEach(function (el) {
                    el.name = el.name.replace(/^projects\[\d+\]/, 'projects[' + i + ']');
                });
            });
        }

        function addHeroRow() {
            var container = document.getElementById('hero-rows');
            var n = container.querySelectorAll('[data-hero-row]').length;
            var html = document.getElementById('tpl-hero-row').innerHTML.replace(/__IDX__/g, String(n));
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeHeroRow(btn) {
            var container = document.getElementById('hero-rows');
            var row = btn.closest('[data-hero-row]');
            if (container.querySelectorAll('[data-hero-row]').length <= 1) {
                row.querySelectorAll('input[type="text"], textarea').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="file"]').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="hidden"][name$="[id]"]').forEach(function (e) { e.remove(); });
                row.querySelectorAll('input[type="checkbox"]').forEach(function (e) { e.checked = false; });
                return;
            }
            row.remove();
            reindexHeroRows();
        }

        function addShopRow() {
            var container = document.getElementById('shop-rows');
            var n = container.querySelectorAll('[data-shop-row]').length;
            var html = document.getElementById('tpl-shop-row').innerHTML.replace(/__IDX__/g, String(n));
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeShopRow(btn) {
            var container = document.getElementById('shop-rows');
            var row = btn.closest('[data-shop-row]');
            if (container.querySelectorAll('[data-shop-row]').length <= 1) {
                row.querySelectorAll('input[type="text"]').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="file"]').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="hidden"][name$="[id]"]').forEach(function (e) { e.remove(); });
                return;
            }
            row.remove();
            reindexShopRows();
        }

        function addFeaturedRow() {
            var container = document.getElementById('featured-rows');
            var n = container.querySelectorAll('[data-featured-row]').length;
            var html = document.getElementById('tpl-featured-row').innerHTML.replace(/__IDX__/g, String(n));
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeFeaturedRow(btn) {
            var container = document.getElementById('featured-rows');
            var row = btn.closest('[data-featured-row]');
            if (container.querySelectorAll('[data-featured-row]').length <= 1) {
                row.querySelectorAll('input[type="text"]').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="file"]').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="hidden"][name$="[id]"]').forEach(function (e) { e.remove(); });
                return;
            }
            row.remove();
            reindexFeaturedRows();
        }

        function addProjectRow() {
            var container = document.getElementById('project-rows');
            var n = container.querySelectorAll('[data-project-row]').length;
            var html = document.getElementById('tpl-project-row').innerHTML.replace(/__IDX__/g, String(n));
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeProjectRow(btn) {
            var container = document.getElementById('project-rows');
            var row = btn.closest('[data-project-row]');
            if (container.querySelectorAll('[data-project-row]').length <= 1) {
                row.querySelectorAll('input[type="text"], textarea').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="file"]').forEach(function (e) { e.value = ''; });
                row.querySelectorAll('input[type="hidden"][name$="[id]"]').forEach(function (e) { e.remove(); });
                return;
            }
            row.remove();
            reindexProjectRows();
        }
    </script>
@endsection
