@php
    $cartCount = is_array(session('cart')) ? count(session('cart')) : 0;
@endphp

<!-- NAVBAR - White Premium Design -->
<nav x-data="{ mobileMenuOpen: false }" class="bg-white py-4 sticky top-0 z-50 border-b border-[#E8E8E3] shadow-sm">
    <div style="max-width: 1280px; margin: 0 auto;" class="px-6 flex items-center justify-between">

        <!-- BRAND -->
        <div class="w-auto lg:w-[180px] flex items-center justify-start">
            <a href="/" class="flex items-center">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax Logo" class="h-6 md:h-7">
            </a>
        </div>

        <!-- DESKTOP NAV (CENTER) -->
        <div class="hidden lg:flex flex-1 items-center justify-center gap-6 text-[10px] font-bold tracking-widest uppercase text-[#111111]">
            <a href="/" class="{{ request()->is('/') ? 'text-[#155EEF] font-black' : 'text-[#666666] hover:text-[#111111]' }} transition-all duration-200 py-2 inline-block">HOME</a>

            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <a href="{{ route('products.index') }}" class="{{ request()->is('products*') && request('category') != 'SALE' ? 'text-[#155EEF] font-black' : 'text-[#666666] hover:text-[#111111]' }} transition-all duration-200 py-2 inline-block flex items-center gap-1">
                    SHOP
                </a>
                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" 
                    class="absolute left-0 mt-0 w-48 bg-white border border-[#E8E8E3] rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('products.index') }}?shop_by=sport" class="block px-4 py-3 hover:bg-[#F7F7F5] transition-colors text-[10px] font-bold tracking-widest text-[#111111]">Shop by Sport</a>
                    <a href="{{ route('products.index') }}?shop_by=product" class="block px-4 py-3 hover:bg-[#F7F7F5] transition-colors text-[10px] font-bold tracking-widest text-[#111111]">Shop by Product</a>
                </div>
            </div>
            
            <a href="{{ route('pages.customization') }}" class="{{ request()->is('customization*') ? 'text-[#155EEF] font-black' : 'text-[#666666] hover:text-[#111111]' }} transition-all duration-200 py-2 inline-block">CUSTOM TEAMWEAR</a>

            <a href="{{ route('pages.contact-us') }}" class="{{ request()->is('contact-us*') ? 'text-[#155EEF] font-black' : 'text-[#666666] hover:text-[#111111]' }} transition-all duration-200 py-2 inline-block">CONTACT</a>

            <a href="{{ route('pages.size-guide') }}" class="{{ request()->is('size-guide*') ? 'text-[#155EEF] font-black' : 'text-[#666666] hover:text-[#111111]' }} transition-all duration-200 py-2 inline-block">SIZE GUIDE</a>

            <a href="{{ route('products.index', ['category' => 'SALE']) }}" class="italic {{ request('category') == 'SALE' ? 'text-rose-600 font-black' : 'text-rose-500 hover:text-rose-600' }} transition-all duration-200 py-2 inline-block">SALE</a>
        </div>

        <!-- DESKTOP RIGHT (ICONS) -->
        <div class="hidden lg:flex items-center justify-end gap-6 w-[200px] text-[#111111]">
            
            <!-- Search -->
            <div class="relative" x-data="{ searchOpen: false }" @click.away="searchOpen = false">
                <button @click="searchOpen = !searchOpen" class="hover:text-[#155EEF] transition-colors flex items-center" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <div x-show="searchOpen" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" 
                    class="absolute right-0 top-full mt-3 w-72 bg-white border border-[#E8E8E3] rounded-lg p-4 shadow-lg z-50">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-lg pl-4 pr-10 py-3 text-xs font-semibold focus:outline-none focus:border-[#155EEF]" autofocus>
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#999999] hover:text-[#155EEF]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart Desktop -->
            <a href="{{ route('cart.show') }}" class="relative hover:text-[#155EEF] transition-colors" title="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                @if ($cartCount)
                    <span class="absolute -top-2 -right-2 bg-[#155EEF] text-white text-[9px] font-black h-5 min-w-[20px] px-1 rounded-full flex items-center justify-center shadow-md">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <div class="h-4 w-px bg-[#E8E8E3]"></div>

            <!-- Currency Desktop -->
            <div class="relative" x-data="{ open: false }" @click.away="open=false">
                <button @click="open=!open" class="flex items-center gap-1.5 hover:text-[#155EEF] transition-colors font-bold uppercase tracking-widest text-xs">
                    {{ session('currency', 'MYR') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute right-0 mt-2 w-40 bg-white border border-[#E8E8E3] rounded-lg shadow-lg overflow-hidden p-2 z-50">
                    @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                        <button onclick="setCurrency('{{ $curr }}')"
                            class="flex w-full items-center justify-between px-3 py-2.5 text-xs font-bold uppercase tracking-widest rounded-md transition-all
                                               {{ session('currency', 'MYR') == $curr ? 'bg-[#155EEF] text-white font-black' : 'text-[#111111] hover:bg-[#F7F7F5]' }}">
                            {{ $curr }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- MOBILE RIGHT -->
        <div class="lg:hidden flex items-center gap-6">
            <a href="{{ route('cart.show') }}" class="relative text-[#111111]">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                @if ($cartCount)
                    <span
                        class="absolute -top-2 -right-2 bg-[#155EEF] text-white text-[9px] font-black h-5 min-w-[20px] px-1 rounded-full flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-[#111111]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="!mobileMenuOpen"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-show="mobileMenuOpen" style="display:none"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="mobileMenuOpen" x-transition x-cloak
        class="lg:hidden bg-[#F7F7F5] border-t border-[#E8E8E3] shadow-md max-h-[80vh] overflow-y-auto">
        <div class="flex flex-col p-6 space-y-4">

            <a @click="mobileMenuOpen=false" href="/"
                class="{{ request()->is('/') ? 'text-[#155EEF] font-black' : 'text-[#111111]' }} font-black uppercase tracking-widest text-sm py-2 border-b border-[#E8E8E3]">Home</a>

            <div x-data="{ productsOpen: {{ request()->is('products*') && request('category') != 'SALE' ? 'true' : 'false' }} }">
                <button @click="productsOpen = !productsOpen" class="w-full flex items-center justify-between {{ request()->is('products*') && request('category') != 'SALE' ? 'text-[#155EEF] font-black' : 'text-[#111111]' }} font-black uppercase tracking-widest text-sm py-2 border-b border-[#E8E8E3]">
                    SHOP
                </button>
                <div x-show="productsOpen" class="pl-4 space-y-2 py-2 border-b border-[#E8E8E3]">
                    <a @click="mobileMenuOpen=false" href="{{ route('products.index') }}?shop_by=sport" class="block text-[#666666] hover:text-[#111111] font-bold uppercase tracking-widest text-xs py-2">Shop by Sport</a>
                    <a @click="mobileMenuOpen=false" href="{{ route('products.index') }}?shop_by=product" class="block text-[#666666] hover:text-[#111111] font-bold uppercase tracking-widest text-xs py-2">Shop by Product</a>
                </div>
            </div>
            
            <a @click="mobileMenuOpen=false" href="{{ route('pages.customization') }}"
                class="{{ request()->is('customization*') ? 'text-[#155EEF] font-black' : 'text-[#111111]' }} font-black uppercase tracking-widest text-sm py-2 border-b border-[#E8E8E3]">Custom Teamwear</a>

            <a @click="mobileMenuOpen=false" href="{{ route('pages.size-guide') }}"
                class="{{ request()->is('size-guide*') ? 'text-[#155EEF] font-black' : 'text-[#111111]' }} font-black uppercase tracking-widest text-sm py-2 border-b border-[#E8E8E3]">Size Guide</a>

            <a @click="mobileMenuOpen=false" href="{{ route('pages.contact-us') }}"
                class="{{ request()->is('contact-us*') ? 'text-[#155EEF] font-black' : 'text-[#111111]' }} font-black uppercase tracking-widest text-sm py-2 border-b border-[#E8E8E3]">Contact Us</a>

            <a @click="mobileMenuOpen=false" href="{{ route('products.index', ['category' => 'SALE']) }}"
                class="italic {{ request('category') == 'SALE' ? 'text-rose-600 font-black' : 'text-rose-500' }} font-black uppercase tracking-widest text-sm py-2">Sale</a>

            <!-- Currency Mobile -->
            <div class="pt-4 border-t border-[#E8E8E3] mt-4">
                <p class="text-xs font-black text-[#666666] mb-4 uppercase tracking-widest">Currency</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                        <button onclick="setCurrency('{{ $curr }}')"
                            class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest
                                    {{ session('currency', 'MYR') == $curr ? 'bg-[#155EEF] text-white' : 'bg-white text-[#111111] border border-[#E8E8E3] hover:border-[#999999]' }}">
                            {{ $curr }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>
