<!-- Footer -->
<footer x-show="!showSplash" class="bg-slate-900 border-t border-white/5 pt-24 pb-12 px-6 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8 mb-20">
            <!-- 1. Shop -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Shop</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">New Arrivals</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Football Series']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Football Series</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Golf Series']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Golf Series</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Fishing Series']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Fishing Series</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Basketball Series']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Basketball Series</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Outdoor Series']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Outdoor Series</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Run and Training Series']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Run & Training</a></li>
                    <li><a href="{{ route('products.index', ['sport' => 'Casual / Lifestyle']) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Casual / Lifestyle</a></li>
                    <li><a href="https://maxumax.my/products?category=SALE" class="text-red-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Sale</a></li>
                </ul>
            </div>

            <!-- 2. Products -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Products</h4>
                <ul class="space-y-4">
                    @foreach(['Jerseys', 'Polos', 'Shirts', 'Windbreakers', 'Tracksuits', 'Jackets', 'Pants', 'Socks', 'Accessories'] as $item)
                    <li><a href="{{ route('products.index', ['category' => $item]) }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- 3. Support -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Support</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('pages.customization') }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Teamwear Customization</a></li>
                    <li><a href="{{ route('order.track') }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Track Order</a></li>
                    <li><a href="{{ route('pages.size-guide') }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Size Guide</a></li>
                    <li><a href="{{ route('pages.policies') }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Policies & Terms</a></li>
                    <li><a href="{{ route('pages.faq') }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">FAQ</a></li>
                    <li><a href="{{ route('pages.contact-us') }}" class="text-slate-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- 4. Contact -->
            <div id="footer-contact">
                <a href="/" class="inline-block mb-6">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Maxumax Logo" class="h-8 w-auto invert brightness-0">
                </a>
                <ul class="space-y-6">
                    <!-- Outlet -->
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-400 mt-1 shrink-0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <div class="flex flex-col">
                            <strong class="text-white font-bold text-xs uppercase tracking-widest mb-1">Outlet</strong>
                            <span class="text-slate-400 text-xs leading-relaxed">
                                Maxumax Store, Unit No. 1-35, 1st Floor,<br>Suria Sabah Shopping Mall, Jalan Tun Fuad Stephens,<br>88000 Kota Kinabalu, Sabah, Malaysia
                            </span>
                        </div>
                    </li>
                    <!-- Office -->
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-400 mt-1 shrink-0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <div class="flex flex-col">
                            <strong class="text-white font-bold text-xs uppercase tracking-widest mb-1">Office / HQ</strong>
                            <span class="text-slate-400 text-xs leading-relaxed">
                                Maxumax PLT, No. A3-2, 1st Floor, Block A,<br>Kepayan Perdana, 88200 Kota Kinabalu,<br>Sabah, Malaysia
                            </span>
                        </div>
                    </li>
                    <!-- Contact Info -->
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 mt-0.5 shrink-0"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span class="text-slate-400 text-xs font-bold">+60 14-343 6496</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mt-0.5 shrink-0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="text-slate-400 text-xs font-bold">contact@maxumax.my</span>
                    </li>
                </ul>
                <!-- Socials -->
                <div class="flex gap-3 mt-6">
                    <a href="https://www.instagram.com/maxumax.my/" target="_blank" class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="https://www.facebook.com/maxumax.my/" target="_blank" class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="https://www.tiktok.com/@maxumax.my" target="_blank" class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center text-white hover:bg-blue-600 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">
                &copy; 2026 {{ config('app.name', 'Maxumax') }} Pro. All Rights Reserved.
            </p>
            <div class="flex gap-8">
                <a href="{{ route('pages.policies') }}" class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Policies</a>
                <a href="{{ route('pages.policies') }}" class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Terms</a>
            </div>
        </div>
    </div>
</footer>
