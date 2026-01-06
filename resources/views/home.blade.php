@extends('layouts.public')

@section('title', 'MaxuMax - Exclusive Jersey & Pre-order')

@section('content')
    <section class="home-hero">
        <div class="section-container">
            <h2 class="hero-title">Exclusive MaxuMax Jerseys</h2>
            <p class="hero-text">Get our premium jerseys through exclusive pre-order. Best quality at affordable prices, pay on delivery.</p>
            <a href="{{ route('preorder.landing') }}" class="inline-block bg-black text-white px-5 py-3 rounded-md font-semibold text-base transition hover:bg-slate-900 hover:-translate-y-0.5">Start Pre-order Now</a>
        </div>
    </section>

    <section class="features-section">
        <div class="section-container">
            <h2 class="section-title">Why Choose MaxuMax?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">✨</div>
                    <h3 class="feature-title">Premium Quality</h3>
                    <p class="feature-text">High-quality materials with neat stitching and durability for long-term use.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">Affordable Prices</h3>
                    <p class="feature-text">Pre-order with competitive pricing and flexible payment, pay when goods arrive.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3 class="feature-title">Full Customization</h3>
                    <p class="feature-text">Choose size, color, add nameset, long sleeve, and personalize to your liking.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📦</div>
                    <h3 class="feature-title">Guaranteed Delivery</h3>
                    <p class="feature-text">Your items will be delivered directly to you with flexible payment options.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3 class="feature-title">Exclusive Designs</h3>
                    <p class="feature-text">Unique and exclusive designs you won't find anywhere else.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3 class="feature-title">Customer Support</h3>
                    <p class="feature-text">Our support team is ready to help you 24/7 via WhatsApp and customer service.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="section-container">
            <div class="about-grid">
                <div>
                    <h2 class="about-title">About MaxuMax</h2>
                    <p class="about-text">MaxuMax is an exclusive jersey brand dedicated to delivering the best quality at affordable prices. We understand your need for high-quality jerseys with attractive designs.</p>
                    <p class="about-text">Through our pre-order system, we guarantee quality for every product and offer the best prices without compromising quality. Join thousands of satisfied customers with our products.</p>
                    <a href="{{ route('preorder.landing') }}" class="inline-block bg-black text-white px-4 py-2 rounded-md font-semibold text-sm transition hover:bg-slate-900 hover:-translate-y-0.5">View Our Collection →</a>
                </div>
                <div class="about-image-card">
                    <div class="about-image">👕</div>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="section-container">
            <div class="stats-grid">
                <div class="stat-block">
                    <h3>1000+</h3>
                    <p>Satisfied Customers</p>
                </div>
                <div class="stat-block">
                    <h3>4+</h3>
                    <p>Jersey Designs</p>
                </div>
                <div class="stat-block">
                    <h3>100%</h3>
                    <p>Satisfaction Guaranteed</p>
                </div>
            </div>
        </div>
    </section>

    <section class="how-section">
        <div class="section-container">
            <h2 class="section-title">How Does Pre-ordering Work?</h2>
            <div class="how-grid">
                <div class="how-card">
                    <div class="how-step-number">1</div>
                    <h3>Choose Jersey</h3>
                    <p class="feature-text">Select the jersey and design you want from our collection.</p>
                </div>
                <div class="how-card">
                    <div class="how-step-number">2</div>
                    <h3>Fill Details</h3>
                    <p class="feature-text">Complete your personal details and jersey specifications (size, color, nameset, etc).</p>
                </div>
                <div class="how-card">
                    <div class="how-step-number">3</div>
                    <h3>Confirmation</h3>
                    <p class="feature-text">We will contact you to confirm details and payment information.</p>
                </div>
                <div class="how-card">
                    <div class="how-step-number">4</div>
                    <h3>Payment</h3>
                    <p class="feature-text">Pay when we arrive at your location with flexible payment methods.</p>
                </div>
                <div class="how-card">
                    <div class="how-step-number">5</div>
                    <h3>Delivery</h3>
                    <p class="feature-text">Your jerseys will be delivered directly according to the schedule agreed.</p>
                </div>
                <div class="how-card">
                    <div class="how-step-number">6</div>
                    <h3>Enjoy</h3>
                    <p class="feature-text">Receive quality jerseys and enjoy a satisfying shopping experience.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Feedback & Rating</h2>
                <p class="text-slate-600">Beri kami penilaian dan masukan untuk meningkatkan kualitas layanan.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 bg-slate-50 border border-slate-200 rounded-xl p-6">
                    <div class="text-slate-900 font-bold text-xl mb-2">Rata-rata Rating</div>
                    @php $avg = $feedbackAvg ?? 0; $count = $feedbackCount ?? 0; $rounded = (int) round($avg); @endphp
                    <div class="flex items-center gap-2 mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $rounded ? 'text-yellow-500' : 'text-slate-300' }}">★</span>
                        @endfor
                    </div>
                    <div class="text-slate-600 text-sm">Skor: {{ number_format($avg, 2) }} dari {{ $count }} feedback</div>
                </div>
                <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-6">
                    <form method="POST" action="{{ route('feedback.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama (opsional)</label>
                                <input type="text" name="name" class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Email (opsional)</label>
                                <input type="email" name="email" class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Rating</label>
                            <div class="flex items-center gap-3">
                                @for ($r = 1; $r <= 5; $r++)
                                    <label class="inline-flex items-center gap-1 cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $r }}" class="accent-black" required />
                                        <span class="text-slate-800">{{ $r }}</span>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Feedback</label>
                            <textarea name="comment" rows="3" class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" placeholder="Tulis masukan Anda di sini..."></textarea>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center bg-black text-white px-5 py-3 rounded-md font-semibold text-base transition hover:bg-slate-900 hover:-translate-y-0.5">Kirim Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-10">
                <div class="text-slate-900 font-bold text-xl mb-4">Feedback Terbaru</div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($latestFeedback ?? [] as $fb)
                        @php $rr = (int) $fb->rating; @endphp
                        <div class="bg-white border border-slate-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold text-slate-900">{{ $fb->name ?? 'Anonim' }}</div>
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $rr ? 'text-yellow-500' : 'text-slate-300' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-slate-600 text-sm">{{ $fb->comment ?? '-' }}</div>
                            <div class="text-slate-400 text-xs mt-2">{{ $fb->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-slate-500">Belum ada feedback.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="section-container cta-container">
            <h2>Ready to Pre-order Your Exclusive Jersey?</h2>
            <p class="cta-text">Don't miss the golden opportunity to get premium jerseys at affordable prices. Pre-order now and get exclusive limited designs.</p>
            <a href="{{ route('preorder.landing') }}" class="inline-block bg-white text-black px-5 py-3 rounded-md font-semibold text-base transition hover:bg-slate-100 hover:-translate-y-0.5">Start Pre-order Now →</a>
        </div>
    </section>
@endsection
