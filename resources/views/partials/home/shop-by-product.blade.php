<!-- 3. Shop By Product -->
<section class="bg-black py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">Shop By Product</h2>
            <p class="text-slate-400 font-medium mt-4">Explore our complete range of high-performance apparel.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 md:gap-4">
            @foreach (['Jerseys', 'Pro Jerseys', 'Polos', 'Windbreakers', 'Tracksuits', 'Jackets', 'Pants', 'Base Layer / Inner', 'Cotton Series', 'Socks', 'Sleeve Socks', 'Caps', 'Accessories'] as $cat)
            <a href="{{ route('products.index', ['category' => $cat]) }}" class="px-6 py-3 bg-[#111] hover:bg-white hover:text-black border border-white/10 text-white rounded-full font-bold text-xs uppercase tracking-widest transition-all duration-300 hover:scale-105">
                {{ $cat }}
            </a>
            @endforeach
        </div>
    </div>
</section>
