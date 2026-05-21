<!-- 5. Trusted Projects / Portfolio -->
<section class="bg-[#050505] py-24 px-6 border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">PROJECTS SHOWCASE</h2>
                <p class="text-slate-400 font-medium mt-3">Trusted by teams, organizations and event partners across Sabah and beyond.</p>
            </div>
            <a href="{{ route('pages.projects') }}" class="inline-flex items-center gap-2 text-white font-bold uppercase tracking-widest text-xs hover:text-slate-400 transition-colors">
                View All Projects <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
        <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none -mx-6 px-6 md:mx-0 md:px-0 md:grid md:grid-cols-2 gap-6">
            @foreach ($trustedProjectItems as $item)
            <div class="w-[85vw] md:w-auto snap-start shrink-0 group relative aspect-[4/3] rounded-2xl overflow-hidden bg-[#111]">
                <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <p class="text-blue-400 font-bold text-xs uppercase tracking-widest mb-1">{{ $item['category'] }}</p>
                    <h4 class="text-white font-black text-lg mb-2 leading-tight">{{ $item['title'] }}</h4>
                    <p class="text-slate-300 text-xs font-medium line-clamp-2">{{ $item['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
