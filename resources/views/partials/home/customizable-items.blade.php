<!-- 12. What We Can Customize -->
<section class="bg-white py-24 md:py-32 px-6 border-t border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="text-center mb-16 md:mb-20">
            <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight mb-4">What We Can Customize</h2>
            <p class="text-[#666666] font-medium text-base md:text-lg">A complete range of apparel and finishing options.</p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-12 md:gap-16">
            <!-- Custom Products -->
            <div>
                <h3 class="text-[#111111] font-black uppercase tracking-widest mb-8 border-b border-[#E8E8E3] pb-4 text-sm md:text-base">Apparel & Merchandise</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 md:gap-4">
                    @foreach(['Custom Jersey', 'Pro Jersey', 'Polo Shirt', 'Tracksuit', 'Windbreaker', 'Jacket', 'Pants', 'Fishing Jersey', 'Running Apparel', 'Basketball Jersey', 'Socks', 'Caps & Bucket Hats', 'Duffle Bag', 'Accessories'] as $item)
                    <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-lg p-4 text-center hover:border-[#155EEF] hover:bg-white transition-all duration-300">
                        <span class="text-[#111111] font-bold text-xs uppercase tracking-wider">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Finishing Options -->
            <div>
                <h3 class="text-[#111111] font-black uppercase tracking-widest mb-8 border-b border-[#E8E8E3] pb-4 text-sm md:text-base">Premium Finishing Options</h3>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        'Sublimation printing', 'Embroidery', 'Heat press logo', 'Silkscreen printing', 
                        'Nameset and numbering', 'Sponsor logo', 'Custom collar', 'Custom ribs', 
                        'Custom sleeve cuffs', 'Special fabric selection', 'Packaging options'
                    ] as $opt)
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#155EEF] shrink-0"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span class="text-[#111111] font-medium text-sm">{{ $opt }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
