<!-- 1. Hero Banner - White Premium Hero + Feature Boxes -->
<section class="bg-white relative">
    
    <!-- Hero Content Area - Split Layout -->
    <div class="px-6 md:px-0" style="max-width: 1280px; margin: 0 auto;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 py-12 md:py-24">
            
            <!-- Left: White Premium Hero Content -->
            <div class="flex flex-col justify-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-[#111111] uppercase tracking-tight mb-3 leading-tight">
                        Performance Teamwear & Lifestyle Sportswear
                    </h1>
                    <p class="text-[#666666] font-medium text-base md:text-lg mb-8 leading-relaxed max-w-lg">
                        Built from Borneo for teams, athletes, clubs, schools, corporations, and active communities.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('products.index') }}" class="bg-[#155EEF] text-white hover:bg-blue-700 px-8 py-3.5 font-black text-xs md:text-sm tracking-widest rounded-lg uppercase transition-all duration-300 hover:scale-105 active:scale-95 shadow-md text-center inline-block">
                            Shop Ready Stock
                        </a>
                        <a href="{{ route('pages.customization') }}" class="bg-white border-2 border-[#111111] text-[#111111] hover:bg-[#F7F7F5] px-8 py-3.5 font-black text-xs md:text-sm tracking-widest rounded-lg uppercase transition-all duration-300 hover:scale-105 active:scale-95 shadow-md text-center inline-block">
                            Start Custom Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Product/Model Image Area -->
            <div class="flex bg-[#E8E8E3] items-center justify-center rounded-xl overflow-hidden aspect-square">
                <video autoplay muted loop playsinline class="w-full h-full object-cover">
                    <source src="{{ asset('assets/mp4/herovid.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>

        <!-- Feature Boxes - 4 Column Layout -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 py-8 md:py-12 border-t border-[#E8E8E3]">
            
            <!-- Box 1: Shop by Sport -->
            <a href="{{ route('products.index') }}?shop_by=sport" class="group relative overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] rounded-lg hover:border-[#111111] hover:shadow-md transition-all aspect-square">
                @if(isset($shopBySportItems[0]) && $shopBySportItems[0]['img'])
                    <img src="{{ $shopBySportItems[0]['img'] }}" alt="Shop by Sport" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-70 transition-opacity duration-300">
                @endif
                <div class="relative inset-0 flex flex-col items-center justify-center p-4 md:p-6 text-center h-full bg-gradient-to-t from-[#111111]/70 via-transparent to-transparent">
                    <h3 class="text-white font-black text-sm md:text-base uppercase tracking-widest">
                        Shop by Sport
                    </h3>
                    <div class="text-white/70 text-xs mt-1">///</div>
                </div>
            </a>

            <!-- Box 2: Ready Stock -->
            <a href="{{ route('products.index') }}?filter=ready-stock" class="group relative overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] rounded-lg hover:border-[#111111] hover:shadow-md transition-all aspect-square">
                @if(isset($shopBySportItems[1]) && $shopBySportItems[1]['img'])
                    <img src="{{ $shopBySportItems[1]['img'] }}" alt="Ready Stock" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-70 transition-opacity duration-300">
                @endif
                <div class="relative inset-0 flex flex-col items-center justify-center p-4 md:p-6 text-center h-full bg-gradient-to-t from-[#111111]/70 via-transparent to-transparent">
                    <h3 class="text-white font-black text-sm md:text-base uppercase tracking-widest">
                        Ready Stock
                    </h3>
                    <div class="text-white/70 text-xs mt-1">///</div>
                </div>
            </a>

            <!-- Box 3: Custom -->
            <a href="{{ route('pages.customization') }}" class="group relative overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] rounded-lg hover:border-[#111111] hover:shadow-md transition-all aspect-square">
                @if(isset($featuredCollectionItems[0]) && $featuredCollectionItems[0]['img'])
                    <img src="{{ $featuredCollectionItems[0]['img'] }}" alt="Custom" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-70 transition-opacity duration-300">
                @endif
                <div class="relative inset-0 flex flex-col items-center justify-center p-4 md:p-6 text-center h-full bg-gradient-to-t from-[#111111]/70 via-transparent to-transparent">
                    <h3 class="text-white font-black text-sm md:text-base uppercase tracking-widest">
                        Custom
                    </h3>
                    <div class="text-white/70 text-xs mt-1">///</div>
                </div>
            </a>

            <!-- Box 4: Gallery -->
            <a href="{{ route('gallery.index') }}" class="group relative overflow-hidden bg-[#F7F7F5] border border-[#E8E8E3] rounded-lg hover:border-[#111111] hover:shadow-md transition-all aspect-square">
                @if(isset($trustedProjectItems[0]) && $trustedProjectItems[0]['img'])
                    <img src="{{ $trustedProjectItems[0]['img'] }}" alt="Gallery" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-70 transition-opacity duration-300">
                @endif
                <div class="relative inset-0 flex flex-col items-center justify-center p-4 md:p-6 text-center h-full bg-gradient-to-t from-[#111111]/70 via-transparent to-transparent">
                    <h3 class="text-white font-black text-sm md:text-base uppercase tracking-widest">
                        Gallery
                    </h3>
                    <div class="text-white/70 text-xs mt-1">///</div>
                </div>
            </a>

        </div>
    </div>

</section>
