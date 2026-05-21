<!-- 1. Hero Banner -->
<section class="bg-black relative">
    <div class="relative w-full h-[60vh] md:h-[85vh] min-h-[400px] md:min-h-[600px] overflow-hidden">
        <!-- Background Video (Sync for Mobile & Desktop) -->
        <div class="absolute inset-0 w-full h-full z-0">
            <video autoplay loop muted playsinline class="w-full h-full object-cover">
                <source src="{{ asset('assets/mp4/herovid.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-black/60 md:bg-black/50"></div>
        </div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 z-10">
            <div class="flex flex-col items-center">
                <h1 class="text-3xl md:text-5xl lg:text-7xl font-black text-white uppercase tracking-tight mb-6 max-w-5xl leading-tight drop-shadow-2xl">
                    {{ $heroSlides[0]['title'] }}
                </h1>
                <p class="text-white/80 font-medium md:text-lg mb-10 max-w-3xl drop-shadow-lg">
                    {{ $heroSlides[0]['text'] }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 w-full sm:w-auto">
                    @foreach ($heroSlides[0]['btns'] as $btn)
                        <a href="{{ $btn['url'] }}" 
                           class="{{ $btn['primary'] ? 'bg-white text-black hover:bg-slate-200' : 'bg-transparent border-2 border-white text-white hover:bg-white hover:text-black' }} px-10 py-5 font-black text-sm md:text-base tracking-[0.1em] rounded-full uppercase transition-all duration-300 hover:scale-105 active:scale-95 shadow-xl text-center">
                            {{ $btn['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
