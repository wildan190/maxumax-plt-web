<!-- Compact Footer - Black Premium Design -->
<footer x-show="!showSplash" class="bg-[#111111] border-t border-[#E8E8E3] pt-16 md:pt-20 pb-8 px-6 mt-16 md:mt-24">
    <div style="max-width: 1280px; margin: 0 auto;">
        
        <!-- Main Footer Content - 4 Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-10 mb-12">
            
            <!-- 1. Shop Links -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-xs mb-6">Shop</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('products.index') }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">All Products</a></li>
                    <li><a href="{{ route('products.index', ['filter' => 'new-arrivals']) }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">New Arrivals</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'SALE']) }}" class="text-rose-500 hover:text-rose-400 text-xs font-bold uppercase tracking-widest transition-colors">Sale</a></li>
                    <li><a href="{{ route('pages.customization') }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">Custom Teamwear</a></li>
                </ul>
            </div>

            <!-- 2. Support Links -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-xs mb-6">Support</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('order.track') }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">Track Order</a></li>
                    <li><a href="{{ route('pages.size-guide') }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">Size Guide</a></li>
                    <li><a href="{{ route('pages.faq') }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">FAQ</a></li>
                    <li><a href="{{ route('pages.policies') }}" class="text-[#999999] hover:text-[#155EEF] text-xs font-bold uppercase tracking-widest transition-colors">Policies</a></li>
                </ul>
            </div>

            <!-- 3. Store & HQ -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-xs mb-6">Store & HQ</h4>
                <ul class="space-y-4">
                    <li>
                        <p class="text-[#E8E8E3] text-xs font-bold mb-1">STORE</p>
                        <p class="text-[#999999] text-xs leading-snug">Suria Sabah Shopping Mall, Kota Kinabalu, Sabah</p>
                    </li>
                    <li>
                        <p class="text-[#E8E8E3] text-xs font-bold mb-1">HQ</p>
                        <p class="text-[#999999] text-xs leading-snug">Kepayan Perdana, Kota Kinabalu, Sabah</p>
                    </li>
                </ul>
            </div>

            <!-- 4. Contact -->
            <div>
                <h4 class="text-white font-black uppercase tracking-widest text-xs mb-6">Contact</h4>
                <ul class="space-y-4">
                    <li>
                        <p class="text-[#E8E8E3] text-xs font-bold mb-2">WhatsApp (Priority)</p>
                        <a href="https://wa.me/60143436496" target="_blank" class="inline-flex items-center gap-2 bg-[#155EEF] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-[#0d46b3] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.634 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat
                        </a>
                    </li>
                    <li>
                        <p class="text-[#E8E8E3] text-xs font-bold mb-1">Email</p>
                        <a href="mailto:contact@maxumax.my" class="text-[#155EEF] hover:text-[#0d46b3] text-xs font-bold">contact@maxumax.my</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-[#E8E8E3] flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-[#666666] font-bold text-xs uppercase tracking-widest">
                &copy; 2026 MAXUMAX. All Rights Reserved.
            </p>
            <div class="flex gap-6">
                <a href="{{ route('pages.policies') }}" class="text-[#666666] hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Policies</a>
                <a href="{{ route('pages.contact-us') }}" class="text-[#666666] hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">Contact</a>
            </div>
        </div>
    </div>
</footer>
