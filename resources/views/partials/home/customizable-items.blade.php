<!-- 12. What We Can Customize -->
<section class="bg-black py-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">What We Can Customize</h2>
            <p class="text-slate-400 font-medium mt-4">A complete range of apparel and finishing options.</p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-16">
            <!-- Custom Products -->
            <div>
                <h3 class="text-white font-black uppercase tracking-widest mb-8 border-b border-white/10 pb-4">Apparel & Merchandise</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach(['Custom Jersey', 'Pro Jersey', 'Polo Shirt', 'Tracksuit', 'Windbreaker', 'Jacket', 'Pants', 'Fishing Jersey', 'Running Apparel', 'Basketball Jersey', 'Socks', 'Caps & Bucket Hats', 'Duffle Bag', 'Accessories'] as $item)
                    <div class="bg-[#111] border border-white/5 rounded-xl p-4 text-center hover:bg-white/5 transition-colors">
                        <span class="text-slate-300 font-bold text-xs uppercase tracking-wider">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Finishing Options -->
            <div>
                <h3 class="text-white font-black uppercase tracking-widest mb-8 border-b border-white/10 pb-4">Premium Finishing Options</h3>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        'Sublimation printing', 'Embroidery', 'Heat press logo', 'Silkscreen printing', 
                        'Nameset and numbering', 'Sponsor logo', 'Custom collar', 'Custom ribs', 
                        'Custom sleeve cuffs', 'Special fabric selection', 'Packaging options'
                    ] as $opt)
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span class="text-slate-300 font-medium text-sm">{{ $opt }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
