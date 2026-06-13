<!-- 6. Why Choose Maxumax -->
<section class="bg-[#F7F7F5] py-20 md:py-28 px-6 border-b border-[#E8E8E3]">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="bg-white p-8 md:p-16 rounded-xl border border-[#E8E8E3] relative overflow-hidden">
            
            <div class="relative z-10">
                <div class="mb-12">
                    <h2 class="text-3xl md:text-4xl font-black text-[#111111] uppercase tracking-tight mb-4">Why Choose Maxumax?</h2>
                    <p class="text-[#155EEF] font-bold uppercase tracking-widest text-xs md:text-sm">Built on quality, speed and dedication to the game</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
                    @foreach ($whyChooseItems as $item)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 shrink-0 bg-[#155EEF] rounded-lg flex items-center justify-center text-white">
                            @if($item['icon'] === 'quality')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            @elseif($item['icon'] === 'custom')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            @elseif($item['icon'] === 'local')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            @elseif($item['icon'] === 'support')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-[#111111] font-bold text-base md:text-lg mb-1">{{ $item['title'] }}</h3>
                            <p class="text-[#666666] text-sm leading-relaxed">{{ $item['description'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
