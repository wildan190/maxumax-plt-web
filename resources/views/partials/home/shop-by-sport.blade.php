<!-- 2. Shop By Sport -->
<section class="bg-[#050505] py-24 px-6 border-b border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">Shop By Sport</h2>
            <p class="text-slate-400 font-medium mt-4">Precision-engineered gear for every discipline.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($shopBySportItems as $sportItem)
            <a href="{{ $sportItem['href'] }}" class="group relative aspect-square rounded-2xl overflow-hidden bg-[#111] border border-white/5 hover:border-white/20 transition-all block">
                <img src="{{ $sportItem['img'] }}" alt="{{ $sportItem['label'] }}" class="w-full h-full object-cover opacity-50 group-hover:opacity-80 transition-opacity duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-t from-black/80 via-transparent to-transparent p-6 text-center">
                    <h3 class="text-white font-black text-sm md:text-lg uppercase tracking-widest leading-tight group-hover:-translate-y-2 transition-transform duration-300">{{ $sportItem['label'] }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
