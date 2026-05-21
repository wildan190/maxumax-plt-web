@extends('layouts.public')

@section('title', 'FAQ - Maxumax')

@section('content')
    <div class="bg-black min-h-screen pt-32 pb-40 px-6 overflow-hidden relative">
        <!-- Background Accents -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[60vh] bg-gradient-to-b from-blue-600/10 via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-4xl mx-auto relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-24 animate-fade-in text-focus-in">
                <span class="text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] mb-6 inline-block">Help Center</span>
                <h1 class="text-5xl md:text-8xl font-black text-white tracking-tighter uppercase italic leading-[0.8] mb-8">
                    Frequently <span class="text-blue-500">Asked.</span>
                </h1>
                <p class="text-white/40 max-w-2xl mx-auto font-black uppercase tracking-widest text-[11px] leading-relaxed">
                    Get quick answers to the questions most commonly asked by our clients.
                </p>
            </div>

            <div class="space-y-4 animate-fade-in-up" x-data="{ active: null }">

                @php
                    $faqGroups = [
                        [
                            'icon' => 'edit-3',
                            'color' => 'blue',
                            'title' => 'Customization',
                            'faqs' => [
                                ['q' => 'What products can be customized?', 'a' => 'We offer a wide range of customizable apparel including jerseys, polo, cotton tee, tracksuits, shorts, caps, socks and merchandises.'],
                                ['q' => 'What is the minimum order quantity?', 'a' => 'Our minimum order quantity for full sublimation teamwear is 8 pieces. But, it may differ depending on the product type and customization requirements.'],
                                ['q' => 'How long is the production period?', 'a' => 'For full sublimation teamwear, production period is 8–14 working days after final design confirmation and payment confirmation. For fully-customized teamwear, please refer to our staff as production period may differ.'],
                                ['q' => 'Do you provide design services?', 'a' => 'Yes, we have our own design team that can assist you based on your ideas, team colours, logos and specific requirements.'],
                                ['q' => 'Do you offer bulk discounts?', 'a' => 'Yes. Our discounts are based on the quantity ordered – the more you order, the higher the discounts given.'],
                                ['q' => 'Can you handle urgent orders?', 'a' => 'Yes, we can accommodate urgent orders depending on our current production schedule and the quantity required. Contact our team with your deadline and order details, and we can advise on the fastest available timeline.'],
                            ]
                        ],
                        [
                            'icon' => 'truck',
                            'color' => 'emerald',
                            'title' => 'Shipping & Delivery',
                            'faqs' => [
                                ['q' => 'How long does delivery take?', 'a' => 'Orders are processed within 1–3 working days after payment confirmation. Delivery times may vary depending on your location and the courier service selected.'],
                                ['q' => 'Do you ship internationally?', 'a' => 'Yes, we ship to Malaysia, Brunei, Singapore, and Indonesia. Please contact us for other destinations.'],
                                ['q' => 'Can I track my order?', 'a' => 'Yes! Once your order is shipped, you will receive a tracking number. You can also use our Track Order page to check your order status anytime.'],
                            ]
                        ],
                        [
                            'icon' => 'refresh-ccw',
                            'color' => 'amber',
                            'title' => 'Returns & Refunds',
                            'faqs' => [
                                ['q' => 'What is your return policy?', 'a' => 'We accept returns within 3–5 days from the date you received your order. Items must be unused, unworn, in original condition, with labels/tags intact and proof of purchase.'],
                                ['q' => 'How do I request a return?', 'a' => 'Contact us via email at maxumax.my@gmail.com with your customer name, order number, reason for return, and clear photos of the item.'],
                                ['q' => 'Are customized items refundable?', 'a' => 'Since customized jerseys are made specifically for you, we cannot accept returns for change of mind. However, manufacturing defects will be replaced immediately at no extra cost.'],
                            ]
                        ],
                        [
                            'icon' => 'credit-card',
                            'color' => 'indigo',
                            'title' => 'Payment',
                            'faqs' => [
                                ['q' => 'What payment methods do you accept?', 'a' => 'We accept Online Bank Transfer, Credit/Debit Cards via Stripe, and Pay on Delivery (COD) for certain regions in Brunei.'],
                                ['q' => 'Is my payment information secure?', 'a' => 'Absolutely. All payments are processed through secure, reputable third-party payment gateways. Maxumax does not store or have access to your credit card or banking details.'],
                            ]
                        ],
                    ];
                @endphp

                @foreach($faqGroups as $gi => $group)
                    @php
                        $colors = [
                            'blue'    => ['bg' => 'bg-blue-500/10',    'border' => 'border-blue-500/20',    'text' => 'text-blue-500'],
                            'emerald' => ['bg' => 'bg-emerald-500/10', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-500'],
                            'amber'   => ['bg' => 'bg-amber-500/10',   'border' => 'border-amber-500/20',   'text' => 'text-amber-500'],
                            'indigo'  => ['bg' => 'bg-indigo-500/10',  'border' => 'border-indigo-500/20',  'text' => 'text-indigo-500'],
                        ];
                        $c = $colors[$group['color']];
                    @endphp
                    <div class="bg-[#111111] border border-white/5 rounded-[3rem] p-10 md:p-16 shadow-3xl">
                        <!-- Section Header -->
                        <div class="flex items-center gap-6 mb-12 border-b border-white/5 pb-8">
                            <div class="w-12 h-12 {{ $c['bg'] }} rounded-2xl flex items-center justify-center border {{ $c['border'] }}">
                                <i data-feather="{{ $group['icon'] }}" class="{{ $c['text'] }} w-6 h-6"></i>
                            </div>
                            <h2 class="text-2xl font-black text-white italic uppercase tracking-tighter">{{ $group['title'] }}</h2>
                        </div>

                        <!-- FAQs -->
                        <div class="space-y-3">
                            @foreach($group['faqs'] as $fi => $faq)
                                @php $key = $gi . '_' . $fi; @endphp
                                <div class="border border-white/5 rounded-2xl overflow-hidden transition-all duration-300"
                                    :class="active === '{{ $key }}' ? 'bg-white/5 border-white/15' : ''">
                                    <button @click="active = (active === '{{ $key }}' ? null : '{{ $key }}')"
                                        class="w-full flex items-center justify-between p-5 text-left hover:bg-white/5 transition-colors">
                                        <span class="font-bold text-base text-white pr-8">{{ $faq['q'] }}</span>
                                        <i data-feather="chevron-down" class="{{ $c['text'] }} opacity-60 transition-transform duration-300 shrink-0"
                                            :class="active === '{{ $key }}' ? 'rotate-180 opacity-100' : ''"></i>
                                    </button>
                                    <div x-show="active === '{{ $key }}'" x-collapse x-cloak>
                                        <div class="px-5 pb-5 text-white/60 leading-relaxed font-medium text-sm">
                                            {{ $faq['a'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Still Have Questions CTA -->
                <div class="bg-[#111111] border border-white/5 rounded-[3rem] p-10 md:p-16 text-center">
                    <h3 class="text-3xl font-black text-white italic uppercase tracking-tighter mb-4">Still Have Questions?</h3>
                    <p class="text-white/40 font-black uppercase tracking-widest text-[11px] leading-relaxed max-w-lg mx-auto mb-10">
                        Visit our complete FAQ page or contact our team for assistance.
                    </p>
                    <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20have%20a%20question."
                        target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-3 bg-white text-black font-black uppercase tracking-wider px-8 py-4 rounded-full hover:bg-slate-200 transition-all duration-300 hover:scale-105 active:scale-95 text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Contact Our Team
                    </a>
                </div>
            </div>

            <!-- Footer ID -->
            <p class="mt-20 text-center text-[9px] text-white/10 font-black uppercase tracking-[0.5em]">
                MAXUMAX FAQ DATALINK #{{ date('Y.m.d') }}
            </p>
        </div>
    </div>
@endsection
