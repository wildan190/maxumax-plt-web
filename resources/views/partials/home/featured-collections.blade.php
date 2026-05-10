<!-- 7. Featured Collections -->
<section class="bg-[#050505] py-24 px-6 border-y border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">Featured Collections</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(['Official Team Collections', 'Football Series', 'Golf Series', 'Fishing Series', 'Run and Training Series', 'Outdoor Series', 'Sale / Clearance'] as $col)
            <a href="{{ route('products.index', ['filter' => $col]) }}" class="group relative aspect-[16/9] rounded-2xl overflow-hidden bg-[#111] border border-white/5 block">
                <img src="{{ asset('assets/img/banner1.jpeg') }}" alt="{{ $col }}" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <h3 class="text-white font-black text-xl md:text-2xl uppercase tracking-widest leading-tight group-hover:text-blue-400 transition-colors">{{ $col }}</h3>
                    <div class="mt-2 inline-flex items-center gap-2 text-xs font-bold text-white/60 uppercase tracking-widest group-hover:text-white transition-colors">
                        Explore <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
