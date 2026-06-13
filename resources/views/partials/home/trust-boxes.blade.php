<!-- 5. Why Choose MAXUMAX & 6. Production Capability -->
<section class="bg-white py-24 md:py-32 px-6 border-b border-[#E8E8E3] relative overflow-hidden">
    <!-- Background Banner (optional subtle background) -->
    <div class="absolute inset-0 z-0 opacity-5">
        <img src="{{ asset('assets/img/banner2.jpeg') }}" alt="Factory Background" class="w-full h-full object-cover">
    </div>

    <div style="max-width: 1280px; margin: 0 auto;" class="relative z-10">
        <div class="text-center mb-16 md:mb-20">
            <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight mb-4">Why Choose MAXUMAX</h2>
            <p class="text-[#666666] font-medium text-base md:text-lg">Built on quality, speed, and dedication to the game.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mb-8 md:mb-12">
            @foreach([
                ['Local Production Capability', 'In-house production facility in Sabah ensuring fast turnarounds and strict quality control.'],
                ['Custom Material & Finishing', 'Access to elite performance fabrics and premium finishing techniques like sublimation and embroidery.'],
                ['Team Order Support', 'Dedicated account managers for teams, handling everything from sizing to final delivery.'],
                ['Project Experience', 'Trusted by hundreds of schools, clubs, and corporate clients across Malaysia.'],
                ['Dedicated Sales Support', 'Responsive customer service to guide you through ready stock purchases or custom orders.']
            ] as $trust)
            <div class="bg-[#F7F7F5] border border-[#E8E8E3] p-8 rounded-lg hover:border-[#155EEF] hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 bg-[#155EEF]/10 rounded-lg flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#155EEF]"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h3 class="text-[#111111] font-black text-base md:text-lg uppercase tracking-widest mb-3 leading-tight">{{ $trust[0] }}</h3>
                <p class="text-[#666666] text-sm leading-relaxed">{{ $trust[1] }}</p>
            </div>
            @endforeach
            
            <!-- Production Capability Highlight -->
            <div class="bg-[#155EEF] border border-[#155EEF] p-8 rounded-lg text-white flex flex-col justify-center">
                <h3 class="font-black text-xl md:text-2xl uppercase tracking-widest mb-4">Production Capability</h3>
                <div class="text-4xl md:text-5xl font-black mb-4">1,000 - 2,500</div>
                <p class="text-white/90 font-bold text-xs uppercase tracking-widest mb-2">Units Per Week</p>
                <p class="text-white/80 text-sm leading-relaxed">Supported with pre-order management, custom packaging, and fulfillment support directly from our HQ.</p>
            </div>
        </div>
    </div>
</section>
