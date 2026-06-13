<!-- 7. Featured Collections -->
<section class="bg-white py-24 md:py-32 px-6 border-b border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="text-center mb-16 md:mb-20">
            <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight">Featured Collections</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach ($featuredCollectionItems as $col)
            <a href="{{ $col['href'] }}" class="group relative aspect-[16/9] rounded-lg overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] block hover:border-[#155EEF] transition-all duration-300">
                <img src="{{ $col['img'] }}" alt="{{ $col['label'] }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <h3 class="text-white font-black text-xl md:text-2xl uppercase tracking-widest leading-tight group-hover:text-[#155EEF] transition-colors">{{ $col['label'] }}</h3>
                    <div class="mt-2 inline-flex items-center gap-2 text-xs font-bold text-white/70 uppercase tracking-widest group-hover:text-white transition-colors">
                        Explore <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
