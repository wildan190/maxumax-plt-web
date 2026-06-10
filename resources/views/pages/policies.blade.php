@extends('layouts.public')

@section('title', 'Policies & Terms - Maxumax')

@section('content')
    <div class="bg-white min-h-screen pt-32 pb-40 px-6 overflow-hidden relative">
        <!-- Background Accents -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[60vh] bg-gradient-to-b from-[#155EEF]/10 via-transparent to-transparent pointer-events-none">
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-24 animate-fade-in text-focus-in">
                <span class="text-[#155EEF] font-black uppercase tracking-[0.4em] text-[10px] mb-6 inline-block">Legal
                    Framework</span>
                <h1 class="text-5xl md:text-8xl font-black text-[#111111] tracking-tighter uppercase italic leading-[0.8] mb-8">
                    Policies <span class="text-[#155EEF]">& Terms.</span>
                </h1>
                <p class="text-[#666666] max-w-2xl mx-auto font-black uppercase tracking-widest text-[11px] leading-relaxed">
                    By accessing our website and placing an order with us, you agree to the following terms and policies:
                </p>
            </div>

            <div class="space-y-12 animate-fade-in-up">
                <!-- Privacy Policy Section -->
                <div class="bg-white border border-[#E8E8E3] rounded-[3rem] p-10 md:p-16 shadow-3xl">
                    <div class="flex items-center gap-6 mb-12 border-b border-[#E8E8E3] pb-8">
                        <div
                            class="w-12 h-12 bg-[#155EEF]/10 rounded-2xl flex items-center justify-center border border-[#155EEF]/20">
                            <i data-feather="lock" class="text-[#155EEF] w-6 h-6"></i>
                        </div>
                        <h2 class="text-3xl font-black text-[#111111] italic uppercase tracking-tighter">1. Privacy Policy.</h2>
                    </div>

                    <div class="prose prose-invert prose-blue max-w-none space-y-10">
                        <p class="text-[#666666] font-medium leading-relaxed">
                            We respect your privacy and are committed to protecting your personal data in accordance with
                            applicable laws and regulations.
                        </p>

                        <div class="space-y-6">
                            <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-[#155EEF] rounded-full"></span>
                                Information We Collect
                            </h3>
                            <p class="text-[#666666] text-[11px] font-bold leading-relaxed uppercase tracking-widest">
                                When you visit our website or make a purchase, we may collect the following information:
                            </p>
                            <ul
                                class="space-y-3 text-[#666666] font-bold text-[11px] uppercase tracking-widest list-none p-0">
                                <li class="flex items-center gap-3"><i data-feather="check"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Full Name</li>
                                <li class="flex items-center gap-3"><i data-feather="check"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Email Address</li>
                                <li class="flex items-center gap-3"><i data-feather="check"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Phone Number</li>
                                <li class="flex items-center gap-3"><i data-feather="check"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Billing and Shipping Addresses</li>
                                <li class="flex items-center gap-3"><i data-feather="check"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Order and Transaction Details</li>
                                <li class="flex items-center gap-3"><i data-feather="check"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> IP address, device type, and browser
                                    information</li>
                            </ul>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-[#155EEF] rounded-full"></span>
                                How We Use Your Information
                            </h3>
                            <p class="text-[#666666] text-[11px] font-bold leading-relaxed uppercase tracking-widest">
                                Your personal information is used strictly for the following purposes:
                            </p>
                            <ul
                                class="space-y-3 text-[#666666] font-bold text-[11px] uppercase tracking-widest list-none p-0">
                                <li class="flex items-center gap-3"><i data-feather="arrow-right"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> To process, fulfill, and deliver your orders
                                </li>
                                <li class="flex items-center gap-3"><i data-feather="arrow-right"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> To communicate with you regarding orders,
                                    refunds, or customer support</li>
                                <li class="flex items-center gap-3"><i data-feather="arrow-right"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> To improve our website, products, and services
                                </li>
                                <li class="flex items-center gap-3"><i data-feather="arrow-right"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> To detect, prevent, and address fraud or
                                    unauthorized transactions</li>
                                <li class="flex items-center gap-3"><i data-feather="arrow-right"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> To comply with legal and regulatory
                                    requirements</li>
                            </ul>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs flex items-center gap-3">
                                <span class="w-1.5 h-1.5 bg-[#155EEF] rounded-full"></span>
                                Sharing of Information
                            </h3>
                            <p class="text-[#666666] text-[11px] font-bold leading-relaxed uppercase tracking-widest">
                                We do not sell, rent, or trade your personal data. Your information may only be shared with
                                trusted third parties such as:
                            </p>
                            <ul
                                class="space-y-3 text-[#666666] font-bold text-[11px] uppercase tracking-widest list-none p-0">
                                <li class="flex items-center gap-3"><i data-feather="share-2"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Payment service providers</li>
                                <li class="flex items-center gap-3"><i data-feather="share-2"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Shipping and logistics partners</li>
                                <li class="flex items-center gap-3"><i data-feather="share-2"
                                        class="w-3 h-3 text-[#155EEF]/40"></i> Government or regulatory authorities when
                                    required by law</li>
                            </ul>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-3xl p-8 space-y-4">
                                <h3 class="text-[#111111] font-black uppercase tracking-widest text-[10px]">Payment Security
                                </h3>
                                <p
                                    class="text-[#666666] text-[10px] font-bold leading-relaxed uppercase tracking-widest m-0">
                                    All payments are securely processed through reputable third-party payment gateways.
                                    maxumax.my does not store or have access to your credit card or banking details.
                                </p>
                            </div>
                            <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-3xl p-8 space-y-4">
                                <h3 class="text-[#111111] font-black uppercase tracking-widest text-[10px]">Cookies</h3>
                                <p
                                    class="text-[#666666] text-[10px] font-bold leading-relaxed uppercase tracking-widest m-0">
                                    Our website may use cookies to enhance user experience and analyze website traffic. You
                                    may choose to disable cookies through your browser settings.
                                </p>
                            </div>
                        </div>

                        <div class="bg-[#155EEF]/5 border border-[#155EEF]/10 rounded-3xl p-8">
                            <h3 class="text-[#155EEF] font-black uppercase tracking-widest text-xs mb-4">Data Protection</h3>
                            <p class="text-[#666666] text-[11px] font-bold leading-relaxed uppercase tracking-widest m-0">
                                We implement reasonable administrative, technical, and security measures to safeguard your
                                personal information against unauthorized access, misuse, or disclosure.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Refund & Return Section -->
                <div class="bg-white border border-[#E8E8E3] rounded-[3rem] p-10 md:p-16 shadow-3xl">
                    <div class="flex items-center gap-6 mb-12 border-b border-[#E8E8E3] pb-8">
                        <div
                            class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center border border-emerald-500/20">
                            <i data-feather="refresh-ccw" class="text-emerald-600 w-6 h-6"></i>
                        </div>
                        <h2 class="text-3xl font-black text-[#111111] italic uppercase tracking-tighter">2. Refund & Return
                            Policy.</h2>
                    </div>

                    <div class="prose prose-invert prose-emerald max-w-none space-y-10">
                        <p class="text-[#666666] font-medium leading-relaxed">
                            Thank you for shopping with maxumax.my.
                        </p>

                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-1 bg-[#F7F7F5] border border-[#E8E8E3] rounded-3xl p-8">
                                <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs mb-4">Return Period</h3>
                                <p class="text-emerald-600 font-black text-3xl italic tracking-tighter mb-2">3 – 5 DAYS</p>
                                <p class="text-[#666666] text-[10px] font-black uppercase tracking-widest">From the date you
                                    received your order.</p>
                            </div>
                            <div class="flex-1 bg-[#F7F7F5] border border-[#E8E8E3] rounded-3xl p-8">
                                <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs mb-4">Eligibility</h3>
                                <p
                                    class="text-[#666666] text-[11px] font-bold uppercase tracking-widest leading-relaxed m-0">
                                    Items must be unused, unworn, in original condition, labels/tags intact with proof of purchase.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs">How to Request a Return</h3>
                            <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-3xl p-8">
                                <p class="text-[#666666] text-xs font-bold leading-relaxed uppercase tracking-widest mb-4">
                                    CONTACT US VIA EMAIL AT: <span class="text-emerald-600">MAXUMAX.MY@GMAIL.COM</span>
                                </p>
                                <p class="text-[#999999] text-[10px] font-black uppercase tracking-[0.2em] mb-4">Include the
                                    following details:</p>
                                <ul
                                    class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[10px] text-[#666666] font-black uppercase tracking-widest list-none p-0">
                                    <li class="flex items-center gap-3"><i data-feather="user"
                                            class="w-3 h-3 text-emerald-600"></i> Customer Name</li>
                                    <li class="flex items-center gap-3"><i data-feather="hash"
                                            class="w-3 h-3 text-emerald-600"></i> Order Number</li>
                                    <li class="flex items-center gap-3"><i data-feather="file-text"
                                            class="w-3 h-3 text-emerald-600"></i> Reason for Return</li>
                                    <li class="flex items-center gap-3"><i data-feather="camera"
                                            class="w-3 h-3 text-emerald-600"></i> Clear Photos</li>
                                </ul>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs">Refund Process</h3>
                                <ul
                                    class="space-y-3 text-[#666666] font-bold text-[11px] uppercase tracking-widest list-none p-0">
                                    <li class="flex items-start gap-3"><i data-feather="check-circle"
                                            class="w-3 h-3 text-emerald-600 mt-1"></i> <span>Items inspected upon
                                            return</span></li>
                                    <li class="flex items-start gap-3"><i data-feather="check-circle"
                                            class="w-3 h-3 text-emerald-600 mt-1"></i> <span>Issued to original payment
                                            method</span></li>
                                    <li class="flex items-start gap-3"><i data-feather="check-circle"
                                            class="w-3 h-3 text-emerald-600 mt-1"></i> <span>7–14 working days
                                            processing</span></li>
                                </ul>
                            </div>
                            <div class="space-y-6">
                                <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs">Non-Refundable Items
                                </h3>
                                <ul
                                    class="space-y-3 text-[#666666] font-bold text-[11px] uppercase tracking-widest list-none p-0">
                                    <li class="flex items-start gap-3"><i data-feather="x-circle"
                                            class="w-3 h-3 text-red-600/60 mt-1"></i> <span>Promotional or discounted
                                            items</span></li>
                                    <li class="flex items-start gap-3"><i data-feather="x-circle"
                                            class="w-3 h-3 text-red-600/60 mt-1"></i> <span>Used, worn, or damaged
                                            items</span></li>
                                    <li class="flex items-start gap-3"><i data-feather="x-circle"
                                            class="w-3 h-3 text-red-600/60 mt-1"></i> <span>Digital products</span></li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-3xl p-8">
                            <h3 class="text-[#111111] font-black uppercase tracking-widest text-xs mb-4">Shipping Costs</h3>
                            <ul
                                class="space-y-3 text-[#666666] font-bold text-[11px] uppercase tracking-widest list-none p-0">
                                <li>• Return shipping costs are the responsibility of the customer</li>
                                <li>• Original shipping fees are non-refundable</li>
                                <li>• Shipping costs refunded only for defective/incorrect items</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Shipping Policy Section -->
                <div class="bg-white border border-[#E8E8E3] rounded-[3rem] p-10 md:p-16 shadow-3xl">
                    <div class="flex items-center gap-6 mb-12 border-b border-[#E8E8E3] pb-8">
                        <div class="w-12 h-12 bg-[#155EEF]/10 rounded-2xl flex items-center justify-center border border-[#155EEF]/20">
                            <i data-feather="truck" class="text-[#155EEF] w-6 h-6"></i>
                        </div>
                        <h2 class="text-3xl font-black text-[#111111] italic uppercase tracking-tighter">3. Shipping Policy.</h2>
                    </div>

                    <div class="prose prose-invert prose-blue max-w-none space-y-6">
                        <p class="text-[#666666] font-medium leading-relaxed">
                            Orders are processed within 1–3 working days after payment confirmation. Delivery times may vary depending on location and courier service.
                        </p>
                        <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-3xl p-8">
                            <p class="text-[#666666] text-[11px] font-bold leading-relaxed uppercase tracking-widest m-0">
                                maxumax.my is not responsible for delays caused by courier services, customs clearance, weather conditions, or other unforeseen circumstances beyond our control.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Changes & Contact Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Changes to Policies -->
                    <div class="bg-white border border-[#E8E8E3] rounded-[3rem] p-10 md:p-12 shadow-3xl">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center border border-amber-500/20">
                                <i data-feather="edit-3" class="text-amber-700 w-5 h-5"></i>
                            </div>
                            <h2 class="text-xl font-black text-[#111111] uppercase italic tracking-tighter">4. Changes.</h2>
                        </div>
                        <p class="text-[#666666] text-[10px] font-bold leading-relaxed uppercase tracking-widest">
                            maxumax.my reserves the right to amend, update, or modify these policies at any time without prior notice. Any changes will take effect immediately upon being published on this page.
                        </p>
                    </div>

                    <!-- Contact Us -->
                    <div class="bg-white border border-[#E8E8E3] rounded-[3rem] p-10 md:p-12 shadow-3xl">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 bg-[#155EEF]/10 rounded-xl flex items-center justify-center border border-[#155EEF]/20">
                                <i data-feather="message-square" class="text-[#155EEF] w-5 h-5"></i>
                            </div>
                            <h2 class="text-xl font-black text-[#111111] uppercase italic tracking-tighter">5. Contact Us.</h2>
                        </div>
                        <div class="space-y-4">
                            <p class="text-[#666666] text-[10px] font-bold uppercase tracking-widest">If you have any questions, please reach out:</p>
                            <ul class="space-y-3 text-[10px] text-[#666666] font-black uppercase tracking-widest list-none p-0">
                                <li class="flex items-center gap-3"><i data-feather="mail" class="w-3 h-3 text-[#155EEF]"></i> maxumax.my@gmail.com</li>
                                <li class="flex items-center gap-3"><i data-feather="globe" class="w-3 h-3 text-[#155EEF]"></i> https://maxumax.my</li>
                                <li class="flex items-center gap-3"><i data-feather="phone" class="w-3 h-3 text-[#155EEF]"></i> +60143436496</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer ID -->
            <p class="mt-20 text-center text-[9px] text-[#E8E8E3] font-black uppercase tracking-[0.5em]">
                MAXUMAX POLICY DATALINK #{{ date('Y.m.d') }}
            </p>
        </div>
    </div>
@endsection