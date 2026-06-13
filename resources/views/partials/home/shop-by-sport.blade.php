<!-- 3. Main Categories - Featured Collections -->
<section class="bg-[#F7F7F5] py-20 md:py-28 px-6 border-b border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight">Featured Collections</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($shopBySportItems as $sportItem)
            <a href="{{ route('products.index') }}?shop_by=sport" class="group relative aspect-square rounded-xl overflow-hidden bg-white border border-[#E8E8E3] hover:border-[#111111] transition-all">
                <img src="{{ $sportItem['img'] }}" alt="{{ $sportItem['label'] }}" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-opacity duration-500 group-hover:scale-110">
                <div class="absolute inset-0 flex items-end justify-start bg-gradient-to-t from-[#111111]/70 via-[#111111]/30 to-transparent p-4 md:p-6">
                    <h3 class="text-white font-black text-sm md:text-base uppercase tracking-widest leading-tight group-hover:-translate-y-1 transition-transform duration-300">{{ $sportItem['label'] }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
