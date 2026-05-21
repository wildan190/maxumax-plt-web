@php
    $cartCount = is_array(session('cart')) ? count(session('cart')) : 0;
@endphp

<!-- Announcement Bar -->
<div class="bg-white text-black py-2 px-4 text-center text-[10px] md:text-xs font-black uppercase tracking-widest relative z-50">
    SPORTSWEAR CUSTOMIZATION EXPERT | SABAH, MALAYSIA
</div>

<!-- NAVBAR -->
<nav x-data="{ mobileMenuOpen: false }" class="bg-black py-3 sticky top-0 z-50 border-b border-white/5">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">

        <!-- BRAND -->
        <div class="w-auto lg:w-[180px] flex items-center justify-start">
            <a href="/" class="flex items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax Logo" class="h-3.5 md:h-4.5 invert brightness-0">
            </a>
        </div>

        <!-- DESKTOP NAV (CENTER) -->
        <div class="hidden lg:flex flex-1 items-center justify-center gap-2.5 text-[9px] xl:text-[10px] font-bold tracking-[0.12em] uppercase text-white/80">
            <a href="/" class="{{ request()->is('/') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-white' }} transition-all duration-200 py-2 inline-block px-1">HOME</a>

            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="{{ route('products.index') }}" class="{{ request()->is('products*') && request('category') != 'SALE' ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-white' }} transition-all duration-200 py-2 inline-block px-1 flex items-center gap-1">
                    ALL PRODUCTS
                </a>
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" 
                    class="absolute left-0 mt-0 w-48 bg-black border border-white/10 rounded-xl shadow-2xl py-2 z-50">
                    <a href="{{ route('products.index') }}?shop_by=sport" class="block px-4 py-2 hover:bg-white/5 transition-colors text-[9px] font-bold tracking-widest">Shop by Sport</a>
                    <a href="{{ route('products.index') }}?shop_by=product" class="block px-4 py-2 hover:bg-white/5 transition-colors text-[9px] font-bold tracking-widest">Shop by Product</a>
                </div>
            </div>
            
            <a href="{{ route('pages.customization') }}" class="{{ request()->is('customization*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-white' }} transition-all duration-200 py-2 inline-block px-1">TEAMWEAR CUSTOMIZATION</a>

            <a href="{{ route('products.index', ['category' => 'SALE']) }}" class="{{ request('category') == 'SALE' ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-white' }} transition-all duration-200 py-2 inline-block px-1">SALE</a>

            <a href="{{ route('pages.contact-us') }}" class="{{ request()->is('contact-us*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-white' }} transition-all duration-200 py-2 inline-block px-1">CONTACT US</a>
        </div>

        <!-- DESKTOP RIGHT (ICONS) -->
        <div class="hidden lg:flex items-center justify-end gap-5 w-[180px] text-white/80">
            
            <!-- Search -->
            <div class="relative" x-data="{ searchOpen: false }" @click.away="searchOpen = false">
                <button @click="searchOpen = !searchOpen" class="hover:text-white transition-colors flex items-center" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <div x-show="searchOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" 
                    class="absolute right-0 top-full mt-4 w-64 bg-black/95 backdrop-blur-md border border-white/10 rounded-2xl p-3 shadow-[0_20px_50px_rgba(0,0,0,0.8)] z-50">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full bg-black/50 text-white border border-white/10 rounded-xl pl-3 pr-10 py-2.5 text-xs font-semibold focus:outline-none focus:border-white/30" autofocus>
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Desktop -->
            <a href="{{ route('cart.show') }}" class="relative hover:text-white transition-colors" title="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                @if ($cartCount)
                    <span class="absolute -top-2 -right-2 bg-white text-black text-[9px] font-black h-4 min-w-[16px] px-1 rounded-full flex items-center justify-center shadow-lg">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <div class="h-3 w-px bg-white/15"></div>

            <!-- Currency Desktop -->
            <div class="relative" x-data="{ open: false }" @click.away="open=false">
                <button @click="open=!open" class="flex items-center gap-1 hover:text-white transition-colors font-bold uppercase tracking-widest text-[10px]">
                    {{ session('currency', 'MYR') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 mt-4 w-32 bg-black/95 backdrop-blur-md border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.8)] overflow-hidden p-1.5 z-50">
                    @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                        <button onclick="setCurrency('{{ $curr }}')"
                            class="flex w-full items-center justify-between px-3 py-2 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all
                                               {{ session('currency', 'MYR') == $curr ? 'bg-white text-black font-black' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                            {{ $curr }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- MOBILE RIGHT -->
        <div class="lg:hidden flex items-center gap-6">
            <a href="{{ route('cart.show') }}" class="relative text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                @if ($cartCount)
                    <span
                        class="absolute -top-2 -right-2 bg-white text-black text-[9px] font-black h-4 min-w-[16px] px-1 rounded-full flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="!mobileMenuOpen"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="mobileMenuOpen" style="display:none"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="mobileMenuOpen" x-transition x-cloak
        class="lg:hidden bg-[#0a0a0a] border-t border-white/10 shadow-2xl overflow-y-auto max-h-[80vh]">
        <div class="flex flex-col p-6 space-y-4">

            <a @click="mobileMenuOpen=false" href="/"
                class="{{ request()->is('/') ? 'text-white' : 'text-white/60' }} font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Home</a>

            <div x-data="{ productsOpen: {{ request()->is('products*') && request('category') != 'SALE' ? 'true' : 'false' }} }">
                <button @click="productsOpen = !productsOpen" class="w-full flex items-center justify-between {{ request()->is('products*') && request('category') != 'SALE' ? 'text-white' : 'text-white/60' }} font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">
                    ALL PRODUCTS
                </button>
                <div x-show="productsOpen" class="pl-4 space-y-2 py-2 border-b border-white/5">
                    <a @click="mobileMenuOpen=false" href="{{ route('products.index') }}?shop_by=sport" class="block text-white/60 hover:text-white font-bold uppercase tracking-widest text-[10px] py-2">Shop by Sport</a>
                    <a @click="mobileMenuOpen=false" href="{{ route('products.index') }}?shop_by=product" class="block text-white/60 hover:text-white font-bold uppercase tracking-widest text-[10px] py-2">Shop by Product</a>
                </div>
            </div>
            
            <a @click="mobileMenuOpen=false" href="{{ route('pages.customization') }}"
                class="{{ request()->is('customization*') ? 'text-white' : 'text-white/60' }} font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Teamwear Customization</a>

            <a @click="mobileMenuOpen=false" href="{{ route('products.index', ['category' => 'SALE']) }}"
                class="{{ request('category') == 'SALE' ? 'text-white' : 'text-white/60' }} font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Sale</a>

            <a @click="mobileMenuOpen=false" href="{{ route('pages.contact-us') }}"
                class="{{ request()->is('contact-us*') ? 'text-white' : 'text-white/60' }} font-black uppercase tracking-widest text-sm py-2">Contact Us</a>

            <!-- Currency Mobile -->
            <div class="pt-6 border-t border-white/10">
                <p class="text-[10px] font-black text-white/40 mb-4 uppercase tracking-[0.2em]">Currency</p>
                <div class="flex flex-wrap gap-3">
                    @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                        <button onclick="setCurrency('{{ $curr }}')"
                            class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest
                                    {{ session('currency', 'MYR') == $curr ? 'bg-white text-black' : 'bg-white/5 text-white hover:bg-white/10' }}">
                            {{ $curr }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>
