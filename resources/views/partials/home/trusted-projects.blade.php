<!-- 5. Trusted Projects / Portfolio -->
<section class="bg-white py-20 md:py-28 px-6 border-b border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight">Projects Showcase</h2>
                <p class="text-[#666666] font-medium mt-3">Trusted by teams, organizations and event partners across Sabah and beyond.</p>
            </div>
            <a href="{{ route('pages.projects') }}" class="inline-flex items-center gap-2 text-[#155EEF] font-black uppercase tracking-widest text-xs hover:text-[#0d46b3] transition-colors">
                View All Projects <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
        <div class="columns-1 lg:columns-3 gap-6 space-y-6">
            @foreach ($trustedProjectItems as $item)
            <div class="break-inside-avoid">
                <a href="{{ route('pages.projects.detail', \Illuminate\Support\Str::slug($item['title'])) }}" class="group relative block rounded-xl overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] hover:border-[#111111] transition-all">
                    <div class="relative overflow-hidden bg-[#F7F7F5]">
                        <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#111111]/85 via-[#111111]/40 to-transparent"></div>
                    </div>
                    <div class="absolute bottom-4 md:bottom-6 left-4 md:left-6 right-4 md:right-6">
                        <p class="text-[#155EEF] font-bold text-xs uppercase tracking-widest mb-1 group-hover:text-white transition-colors">{{ $item['category'] }}</p>
                        <h4 class="text-white font-black text-base md:text-lg mb-2 leading-tight group-hover:text-[#155EEF] transition-colors">{{ $item['title'] }}</h4>
                        <p class="text-white/70 text-xs font-medium line-clamp-2 max-w-2xl">{{ $item['description'] }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
