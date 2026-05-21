<!-- 5. Trusted Projects / Portfolio -->
<section class="bg-[#050505] py-24 px-6 border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">Trusted Projects</h2>
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
