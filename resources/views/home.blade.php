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
                    <div class="feature-icon"><i data-feather="star"></i></div>
                    <h3 class="feature-title">Premium Quality</h3>
                    <p class="feature-text">High-quality materials with neat stitching and durability for long-term use.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-feather="dollar-sign"></i></div>
                    <h3 class="feature-title">Affordable Prices</h3>
                    <p class="feature-text">Pre-order with competitive pricing and flexible payment, pay when goods arrive.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-feather="sliders"></i></div>
                    <h3 class="feature-title">Full Customization</h3>
                    <p class="feature-text">Choose size, color, add nameset, long sleeve, and personalize to your liking.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-feather="box"></i></div>
                    <h3 class="feature-title">Guaranteed Delivery</h3>
                    <p class="feature-text">Your items will be delivered directly to you with flexible payment options.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-feather="award"></i></div>
                    <h3 class="feature-title">Exclusive Designs</h3>
                    <p class="feature-text">Unique and exclusive designs you won't find anywhere else.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i data-feather="message-circle"></i></div>
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
                    <div class="about-image"><i data-feather="shopping-bag"></i></div>
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

    

    <section class="cta-section">
        <div class="section-container cta-container">
            <h2>Ready to Pre-order Your Exclusive Jersey?</h2>
            <p class="cta-text">Don't miss the golden opportunity to get premium jerseys at affordable prices. Pre-order now and get exclusive limited designs.</p>
            <a href="{{ route('preorder.landing') }}" class="inline-block bg-white text-black px-5 py-3 rounded-md font-semibold text-base transition hover:bg-slate-100 hover:-translate-y-0.5">Start Pre-order Now →</a>
        </div>
    </section>
@endsection
