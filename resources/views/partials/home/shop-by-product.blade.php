<!-- 3. Shop By Product -->
<section class="bg-white py-24 md:py-32 px-6 border-b border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="text-center mb-16 md:mb-20">
            <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight mb-4">Shop By Product</h2>
            <p class="text-[#666666] font-medium text-base md:text-lg">Explore our complete range of high-performance apparel.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 md:gap-4">
            @foreach (['Jerseys', 'Pro Jerseys', 'Polos', 'Shirts', 'Windbreakers', 'Tracksuits', 'Jackets', 'Pants', 'Base Layer / Inner', 'Cotton Series', 'Socks', 'Sleeve Socks', 'Caps', 'Accessories'] as $cat)
            <a href="{{ route('products.index', ['category' => $cat]) }}" class="px-6 py-3 bg-[#F7F7F5] hover:bg-[#155EEF] hover:text-white border border-[#E8E8E3] text-[#111111] hover:border-[#155EEF] rounded-full font-bold text-xs uppercase tracking-widest transition-all duration-300 hover:scale-105">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</section>
