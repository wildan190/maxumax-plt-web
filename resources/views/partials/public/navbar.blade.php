@php
    $cartCount = is_array(session('cart')) ? count(session('cart')) : 0;
@endphp

<!-- Announcement Bar -->
<div x-show="!showSplash" class="bg-white text-black py-2 px-4 text-center text-[10px] md:text-xs font-black uppercase tracking-widest relative z-50">
    Local Sportswear Brand from Kota Kinabalu, Sabah | Ready Stock and Custom Teamwear Available
</div>

<!-- NAVBAR -->
<nav x-show="!showSplash" x-data="{ mobileMenuOpen: false }" class="bg-black py-4 sticky top-0 z-50 border-b border-white/5">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">

        <!-- BRAND -->
        <a href="/" class="flex items-center">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax Logo" class="h-5 md:h-7 invert brightness-0">
        </a>

        <!-- DESKTOP NAV (CENTER) -->
        <div class="hidden lg:flex flex-1 items-center justify-center gap-8 xl:gap-10">
            <a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}"
                class="text-white font-black uppercase tracking-widest text-[10px] xl:text-xs hover:text-slate-400 transition-colors">New Arrivals</a>

            <!-- Shop Mega Dropdown -->
            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="text-white font-black uppercase tracking-widest text-[10px] xl:text-xs hover:text-slate-400 transition-colors flex items-center gap-1">
                    Shop
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div x-show="open" x-cloak x-transition
                    class="absolute left-1/2 -translate-x-1/2 mt-0 w-[480px] bg-slate-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden z-[60] flex">
                    <!-- Shop by Sport Column -->
                    <div class="w-1/2 p-6 border-r border-white/5 bg-slate-900">
                        <h4 class="text-white/40 font-black uppercase tracking-widest text-[9px] mb-4">By Sport</h4>
                        <div class="flex flex-col gap-3">
                            @foreach (['Football Series', 'Golf Series', 'Fishing Series', 'Basketball Series', 'Outdoor Series', 'Run and Training', 'Casual / Lifestyle'] as $sport)
                                <a href="{{ route('products.index', ['sport' => $sport]) }}"
                                    class="text-[10px] font-black uppercase tracking-widest text-white hover:text-blue-400 transition-colors">
                                    {{ $sport }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- Shop by Product Column -->
                    <div class="w-1/2 p-6 bg-[#0a0a0a]">
                        <h4 class="text-white/40 font-black uppercase tracking-widest text-[9px] mb-4">By Product</h4>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach (['Jerseys', 'Pro Jerseys', 'Polos', 'Shirts', 'Windbreakers', 'Tracksuits', 'Jackets', 'Pants', 'Socks', 'Accessories', 'Caps'] as $cat)
                                <a href="{{ route('products.index', ['category' => $cat]) }}"
                                    class="text-[10px] font-black uppercase tracking-widest text-white hover:text-blue-400 transition-colors">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('preorder.landing') }}"
                class="text-white font-black uppercase tracking-widest text-[10px] xl:text-xs hover:text-slate-400 transition-colors">Custom Teamwear</a>

            <a href="{{ route('products.index', ['filter' => 'sale']) }}"
                class="text-red-500 font-black uppercase tracking-widest text-[10px] xl:text-xs hover:text-red-400 transition-colors">Sale</a>
        </div>

        <!-- DESKTOP RIGHT (ICONS) -->
        <div class="hidden lg:flex items-center justify-end gap-6 w-[200px]">
            
            <!-- Search -->
            <div class="relative" x-data="{ searchOpen: false }" @click.away="searchOpen = false">
                <button @click="searchOpen = !searchOpen" class="text-white hover:text-slate-400 transition-colors flex items-center" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <div x-show="searchOpen" x-cloak x-transition class="absolute right-0 top-full mt-6 w-64 bg-slate-900 border border-white/10 rounded-xl p-3 shadow-2xl z-50">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Search products..." class="w-full bg-[#0a0a0a] text-white border border-white/10 rounded-lg pl-3 pr-10 py-2.5 text-xs font-medium focus:outline-none focus:border-white/30" autofocus>
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Icon -->
            <a href="#" class="text-white hover:text-slate-400 transition-colors" title="Account">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </a>

            <!-- Cart Desktop -->
            <a href="{{ route('cart.show') }}" class="relative text-white hover:text-slate-400 transition-colors" title="Cart">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                @if ($cartCount)
                    <span
                        class="absolute -top-2 -right-2 bg-white text-black text-[9px] font-black h-4 min-w-[16px] px-1 rounded-full flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <div class="h-4 w-px bg-white/20"></div>

            <!-- Currency Desktop -->
            <div class="relative" x-data="{ open: false }" @click.away="open=false">
                <button @click="open=!open"
                    class="flex items-center gap-2 text-white font-black uppercase tracking-widest text-[10px]">
                    {{ session('currency', 'MYR') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>

                <div x-show="open" x-cloak x-transition
                    class="absolute right-0 mt-4 w-32 bg-slate-900 border border-white/10 rounded-xl shadow-2xl overflow-hidden">
                    @foreach (['MYR', 'BND', 'SGD', 'IDR'] as $curr)
                        <button onclick="setCurrency('{{ $curr }}')"
                            class="flex w-full items-center justify-between px-4 py-3 text-[10px] font-black uppercase tracking-widest
                                               {{ session('currency', 'MYR') == $curr ? 'bg-white text-black' : 'text-white hover:bg-white/10' }}">
                            {{ $curr }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- MOBILE RIGHT -->
        <div class="lg:hidden flex items-center gap-6">
            <a href="{{ route('cart.show') }}" class="relative text-white">
                <i data-feather="shopping-cart" style="width:20px;height:20px"></i>
                @if ($cartCount)
                    <span
                        class="absolute -top-2 -right-2 bg-white text-black text-[9px] font-black h-4 min-w-[16px] px-1 rounded-full flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white">
                <i data-feather="menu" x-show="!mobileMenuOpen"></i>
                <i data-feather="x" x-show="mobileMenuOpen" style="display:none"></i>
            </button>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="mobileMenuOpen" x-transition x-cloak
        class="lg:hidden bg-[#0a0a0a] border-t border-white/10 shadow-2xl overflow-y-auto max-h-[80vh]">
        <div class="flex flex-col p-6 space-y-4">

            <a @click="mobileMenuOpen=false" href="/"
                class="text-white font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Home</a>
            
            <a @click="mobileMenuOpen=false" href="{{ route('products.index', ['filter' => 'new-arrivals']) }}"
                class="text-white font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">New Arrivals</a>

            <a @click="mobileMenuOpen=false" href="{{ route('products.index') }}"
                class="text-white font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Shop Ready Stock</a>
            
            <a @click="mobileMenuOpen=false" href="{{ route('preorder.landing') }}"
                class="text-white font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Custom Teamwear</a>

            <!-- Shop by Sport Mobile Accordion -->
            <div x-data="{ open: false }" class="border-b border-white/5 py-2">
                <button @click="open = !open" class="flex items-center justify-between w-full text-white font-black uppercase tracking-widest text-sm">
                    <span>Shop by Sport</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="{'rotate-180': open}" class="transition-transform"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-4 flex flex-col space-y-4 bg-white/5 p-4 rounded-xl">
                    @foreach (['Football Series', 'Golf Series', 'Fishing Series', 'Basketball Series', 'Outdoor Series', 'Run and Training Series', 'Casual / Lifestyle'] as $sport)
                        <a @click="mobileMenuOpen=false" href="{{ route('products.index', ['sport' => $sport]) }}"
                            class="text-white/70 font-bold uppercase tracking-widest text-xs hover:text-white">
                            {{ $sport }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Shop by Product Mobile Accordion -->
            <div x-data="{ open: false }" class="border-b border-white/5 py-2">
                <button @click="open = !open" class="flex items-center justify-between w-full text-white font-black uppercase tracking-widest text-sm">
                    <span>Shop by Product</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="{'rotate-180': open}" class="transition-transform"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div x-show="open" x-collapse x-cloak class="mt-4 flex flex-col space-y-4 bg-white/5 p-4 rounded-xl">
                    @foreach (['Jerseys', 'Pro Jerseys', 'Polos', 'Shirts', 'Windbreakers', 'Tracksuits', 'Jackets', 'Pants', 'Base Layer / Inner', 'Cotton Series', 'Socks', 'Sleeve Socks', 'Caps', 'Accessories'] as $cat)
                        <a @click="mobileMenuOpen=false" href="{{ route('products.index', ['category' => $cat]) }}"
                            class="text-white/70 font-bold uppercase tracking-widest text-xs hover:text-white">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a @click="mobileMenuOpen=false" href="{{ route('order.track') }}"
                class="text-white font-black uppercase tracking-widest text-sm py-2 border-b border-white/5">Track Order</a>

            <a @click="mobileMenuOpen=false" href="#footer-contact"
                class="text-white font-black uppercase tracking-widest text-sm py-2">Contact Us</a>

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
