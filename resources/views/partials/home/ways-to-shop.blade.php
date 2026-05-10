<!-- 9. Ready Stock vs Custom Order -->
<section class="bg-[#050505] py-24 px-6 border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">Two Ways to Shop</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Ready Stock Card -->
            <div class="bg-gradient-to-br from-[#1a1a1a] to-[#111] p-10 md:p-16 rounded-3xl border border-white/10 flex flex-col h-full">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-black w-8 h-8"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight mb-4">Ready Stock Apparel</h3>
                <p class="text-slate-400 font-medium leading-relaxed mb-8 flex-grow">
                    Immediate dispatch for individual athletes or teams needing high-performance gear right now. Explore our latest drops, seasonal collections, and limited edition items. No minimum order quantity.
                </p>
                <a href="{{ route('products.index') }}" class="px-8 py-4 bg-white text-black font-black text-sm tracking-[0.1em] rounded-full uppercase transition-all duration-300 hover:bg-slate-200 hover:scale-105 active:scale-95 text-center shadow-xl">
                    Browse Ready Stock
                </a>
            </div>

            <!-- Custom Teamwear Card -->
            <div class="bg-gradient-to-br from-blue-900 to-[#111] p-10 md:p-16 rounded-3xl border border-blue-500/30 flex flex-col h-full">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white w-8 h-8"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                </div>
                <h3 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight mb-4">Custom Teamwear</h3>
                <p class="text-blue-100/70 font-medium leading-relaxed mb-8 flex-grow">
                    Built from the ground up for your specific team, school, or event. Choose your fabrics, designs, logos, and finishing. Bulk orders welcome, supported by our local production team.
                </p>
                <a href="{{ route('preorder.landing') }}" class="px-8 py-4 bg-blue-600 text-white font-black text-sm tracking-[0.1em] rounded-full uppercase transition-all duration-300 hover:bg-blue-500 hover:scale-105 active:scale-95 text-center shadow-xl">
                    Start Custom Order
                </a>
            </div>
        </div>
    </div>
</section>
