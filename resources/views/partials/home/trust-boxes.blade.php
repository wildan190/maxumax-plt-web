<!-- 5. Why Choose MAXUMAX & 6. Production Capability -->
<section class="bg-black py-24 px-6 relative overflow-hidden">
    <!-- Background Banner -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/img/banner2.jpeg') }}" alt="Factory Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-[2px]"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight">Why Choose MAXUMAX</h2>
            <p class="text-slate-400 font-medium mt-4">Built on quality, speed, and dedication to the game.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            @foreach([
                ['Local Production Capability', 'In-house production facility in Sabah ensuring fast turnarounds and strict quality control.'],
                ['Custom Material & Finishing', 'Access to elite performance fabrics and premium finishing techniques like sublimation and embroidery.'],
                ['Team Order Support', 'Dedicated account managers for teams, handling everything from sizing to final delivery.'],
                ['Project Experience', 'Trusted by hundreds of schools, clubs, and corporate clients across Malaysia.'],
                ['Dedicated Sales Support', 'Responsive customer service to guide you through ready stock purchases or custom orders.']
            ] as $trust)
            <div class="bg-[#111] border border-white/10 p-8 rounded-2xl hover:border-white/30 transition-colors">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h3 class="text-white font-black text-lg uppercase tracking-widest mb-3 leading-tight">{{ $trust[0] }}</h3>
                <p class="text-slate-400 text-sm leading-relaxed">{{ $trust[1] }}</p>
            </div>
            @endforeach
            
            <!-- Production Capability Highlight -->
            <div class="bg-blue-600 border border-blue-500 p-8 rounded-2xl text-white flex flex-col justify-center">
                <h3 class="font-black text-2xl uppercase tracking-widest mb-4">Production Capability</h3>
                <div class="text-5xl font-black mb-4">1,000 - 2,500</div>
                <p class="text-blue-100 font-bold text-sm uppercase tracking-widest mb-2">Units Per Week</p>
                <p class="text-white/80 text-sm leading-relaxed">Supported with pre-order management, custom packaging, and fulfillment support directly from our HQ.</p>
            </div>
        </div>
    </div>
</section>
