<!-- 1. Hero Banner -->
<section class="bg-black relative" x-data="{ 
        activeSlide: 0, 
        slides: [
            { 
                img: '{{ asset('assets/img/banner1.jpeg') }}', 
                title: 'PERFORMANCE SPORTSWEAR AND CUSTOM TEAMWEAR FROM SABAH',
                text: 'Ready stock apparel and fully customized teamwear for athletes, clubs, schools, companies, events, and sports organizations.',
                btns: [
                    { label: 'Shop Ready Stock', url: '{{ route('products.index') }}', primary: true },
                    { label: 'Start Custom Order', url: '{{ route('preorder.landing') }}', primary: false }
                ]
            },
            { 
                img: '{{ asset('assets/img/banner2.jpeg') }}', 
                title: 'FULLY CUSTOMIZED TEAMWEAR',
                text: 'From design, fabric selection, sublimation printing, sewing, nameset, logo, and finishing - supported through local production in Kota Kinabalu.',
                btns: [
                    { label: 'Get Team Quotation', url: 'https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear.%0AProduct:%0AQuantity:%0ADeadline:%0ADesign%20idea:%0ALocation:%0ACan%20you%20help%20me%20with%20quotation?', primary: true },
                    { label: 'View Custom Process', url: '#custom-process', primary: false }
                ]
            },
            { 
                img: '{{ asset('assets/img/banner1.jpeg') }}', 
                title: 'READY STOCK FOR SPORT AND LIFESTYLE',
                text: 'Explore MAXUMAX jerseys, pro jerseys, windbreakers, tracksuits, outdoor apparel, fishing series, run and training series, casual wear, jackets, golf shirts, pants, socks, and accessories.',
                btns: [
                    { label: 'Shop Ready Stock', url: '{{ route('products.index') }}', primary: true }
                ]
            },
            { 
                img: '{{ asset('assets/img/banner2.jpeg') }}', 
                title: 'OFFICIAL TEAM AND CLUB COLLECTIONS',
                text: 'Explore selected official teamwear and limited-edition collections.',
                btns: [
                    { label: 'View Collections', url: '{{ route('products.index', ['filter' => 'collections']) }}', primary: true }
                ]
            }
        ],
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
        init() { setInterval(() => this.next(), 6000) }
    }">
    <div class="relative w-full h-screen min-h-[600px] overflow-hidden">
        <!-- Desktop Slider Wrapper -->
        <div class="hidden md:flex h-full transition-transform duration-1000 ease-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
            <template x-for="(slide, index) in slides" :key="index">
                <div class="w-full h-full flex-shrink-0 relative">
                    <img :src="slide.img" alt="Maxumax Hero Banner" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/70"></div>
                </div>
            </template>
        </div>

        <!-- Mobile Background Video -->
        <div class="md:hidden absolute inset-0 w-full h-full z-0">
            <video autoplay loop muted playsinline class="w-full h-full object-cover">
                <source src="{{ asset('assets/mp4/herovid.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-black/70"></div>
        </div>

        <!-- Hero Content -->
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6 z-10">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col items-center">
                    <h1 class="text-3xl md:text-5xl lg:text-7xl font-black text-white uppercase tracking-tight mb-6 max-w-5xl leading-tight drop-shadow-2xl" x-text="slide.title"></h1>
                    <p class="text-white/80 font-medium md:text-lg mb-10 max-w-3xl drop-shadow-lg" x-text="slide.text"></p>
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 w-full sm:w-auto">
                        <template x-for="(btn, bIndex) in slide.btns" :key="bIndex">
                            <a :href="btn.url" :class="btn.primary ? 'bg-white text-black hover:bg-slate-200' : 'bg-transparent border-2 border-white text-white hover:bg-white hover:text-black'" class="px-8 py-4 font-black text-sm md:text-base tracking-[0.1em] rounded-full uppercase transition-all duration-300 hover:scale-105 active:scale-95 shadow-xl text-center" x-text="btn.label"></a>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Slider Dots -->
        <div class="absolute bottom-6 md:bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20">
            <template x-for="(slide, index) in slides" :key="index">
                <button class="h-2 rounded-full transition-all duration-500 ease-out"
                    :class="activeSlide === index ? 'bg-white w-8' : 'bg-white/30 w-2 hover:bg-white/50'"
                    @click="activeSlide = index"></button>
            </template>
        </div>
    </div>
</section>
