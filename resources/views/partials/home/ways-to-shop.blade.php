<!-- 9. Ready Stock vs Custom Order -->
<section class="bg-[#050505] py-16 px-6 border-t border-white/5">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tight">Two Ways to Shop</h2>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Ready Stock Card -->
            <div class="bg-gradient-to-br from-[#1a1a1a] to-[#111] p-8 md:p-10 rounded-2xl border border-white/10 flex flex-col h-full">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-black w-6 h-6"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
                <h3 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight mb-3">Ready Stock Apparel</h3>
                <p class="text-slate-400 font-medium leading-relaxed mb-6 text-sm flex-grow">
                    Immediate dispatch for individual athletes or teams needing high-performance gear right now. Explore our latest drops and seasonal collections.
                </p>
                <a href="{{ route('products.index') }}" class="px-6 py-3.5 bg-white text-black font-black text-xs tracking-[0.1em] rounded-full uppercase transition-all duration-300 hover:bg-slate-200 hover:scale-105 active:scale-95 text-center shadow-lg">
                    Browse Ready Stock
                </a>
            </div>

            <!-- Custom Teamwear Card -->
            <div class="bg-gradient-to-br from-blue-900/40 to-[#111] p-8 md:p-10 rounded-2xl border border-blue-500/20 flex flex-col h-full">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white w-6 h-6"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                </div>
                <h3 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight mb-3">Custom Teamwear</h3>
                <p class="text-blue-100/70 font-medium leading-relaxed mb-6 text-sm flex-grow">
                    Built from the ground up for your specific team or event. Choose your fabrics, designs, and logos. Supported by our local production team.
                </p>
                <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear.%0AProduct:%0AQuantity:%0ADeadline:%0ADesign%20idea:%0ALocation:%0ACan%20you%20help%20me%20with%20quotation?" class="px-6 py-3.5 bg-blue-600 text-white font-black text-xs tracking-[0.1em] rounded-full uppercase transition-all duration-300 hover:bg-blue-500 hover:scale-105 active:scale-95 text-center shadow-lg">
                    Get Team Quotation
                </a>
            </div>
        </div>
    </div>
</section>
