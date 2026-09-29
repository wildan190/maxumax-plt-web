@extends('layouts.public')

@section('title', 'Contact Us - Maxumax')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact Us - Maxumax",
  "description": "Get in touch with Maxumax for custom teamwear, sportswear inquiries, and support. Based in Kota Kinabalu, Sabah.",
  "url": "{{ url()->current() }}",
  "mainEntity": {
    "@type": "LocalBusiness",
    "name": "Maxumax Malaysia",
    "image": "{{ asset('assets/img/logo.png') }}",
    "telephone": "+601131614760",
    "email": "contact@maxumax.my",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Lot 27, Ground Floor, Block D, Plaza 333, Penampang",
      "addressLocality": "Kota Kinabalu",
      "addressRegion": "Sabah",
      "postalCode": "88300",
      "addressCountry": "MY"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 5.9189,
      "longitude": 116.0717
    },
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
      ],
      "opens": "09:00",
      "closes": "18:00"
    },
    "sameAs": [
      "https://www.facebook.com/maxumax.my",
      "https://www.instagram.com/maxumax.my"
    ]
  }
}
</script>
@endpush

@section('content')
<main class="min-h-screen bg-white text-[#111111] pt-12 md:pt-16 pb-24">
    <!-- Header Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-12 md:mb-16">
        <span class="text-[#155EEF] font-black uppercase tracking-[0.3em] text-[10px] mb-3 inline-block">Direct Communication</span>
        <h1 class="text-3xl md:text-5xl lg:text-6xl font-black uppercase tracking-tight text-[#111111] mb-4">
            Contact <span class="text-[#155EEF]">Us</span>
        </h1>
        <p class="text-sm md:text-base text-[#666666] font-medium max-w-xl mx-auto leading-relaxed">
            Have a question about custom teamwear, bulk orders, or order status? Reach out to our team in Kota Kinabalu, Sabah.
        </p>
    </section>

    <!-- Main Content Container -->
    <section style="max-width: 1280px; margin: 0 auto;" class="px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            
            <!-- Left Column: Contact Channels & Location Information (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Quick Contact Cards -->
                <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-xl p-6 md:p-8">
                    <h2 class="text-xs font-black uppercase tracking-widest text-[#111111] mb-6 pb-3 border-b border-[#E8E8E3]">
                        Direct Channels
                    </h2>

                    <div class="space-y-6">
                        <!-- WhatsApp -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#155EEF]/10 border border-[#155EEF]/20 flex items-center justify-center shrink-0 text-[#155EEF]">
                                <i data-feather="message-circle" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#999999] mb-1">WhatsApp Chat</p>
                                <p class="text-sm font-bold text-[#111111] mb-2">+60 14-343 6496</p>
                                <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20have%20an%20inquiry." target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center gap-1.5 text-xs font-black text-[#155EEF] hover:text-[#0d46b3] uppercase tracking-wider transition-colors">
                                    Chat Now
                                    <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#155EEF]/10 border border-[#155EEF]/20 flex items-center justify-center shrink-0 text-[#155EEF]">
                                <i data-feather="mail" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#999999] mb-1">Email Inquiries</p>
                                <a href="mailto:contact@maxumax.my" class="text-sm font-bold text-[#111111] hover:text-[#155EEF] transition-colors block truncate mb-1">
                                    contact@maxumax.my
                                </a>
                                <p class="text-[11px] text-[#666666]">Response within 1–2 business days</p>
                            </div>
                        </div>

                        <!-- Business Hours -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#155EEF]/10 border border-[#155EEF]/20 flex items-center justify-center shrink-0 text-[#155EEF]">
                                <i data-feather="clock" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#999999] mb-1">Working Hours</p>
                                <p class="text-sm font-bold text-[#111111]">Mon – Sat: 9:00 AM – 6:00 PM</p>
                                <p class="text-[11px] text-[#666666]">Sunday & Public Holidays Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Locations Card -->
                <div class="bg-white border border-[#E8E8E3] rounded-xl p-6 md:p-8">
                    <h2 class="text-xs font-black uppercase tracking-widest text-[#111111] mb-6 pb-3 border-b border-[#E8E8E3]">
                        Our Presence
                    </h2>

                    <div class="space-y-5">
                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-lg bg-[#F7F7F5] border border-[#E8E8E3] flex items-center justify-center shrink-0 text-[#666666]">
                                <i data-feather="shopping-bag" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#155EEF] mb-0.5">Retail Store</p>
                                <p class="text-xs font-bold text-[#111111]">Suria Sabah Shopping Mall</p>
                                <p class="text-xs text-[#666666] leading-snug">Kota Kinabalu, Sabah, Malaysia</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-lg bg-[#F7F7F5] border border-[#E8E8E3] flex items-center justify-center shrink-0 text-[#666666]">
                                <i data-feather="map-pin" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#155EEF] mb-0.5">HQ & Production</p>
                                <p class="text-xs font-bold text-[#111111]">Kepayan Perdana / Plaza 333</p>
                                <p class="text-xs text-[#666666] leading-snug">Penampang, Kota Kinabalu, Sabah</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Assistance Banner -->
                <div class="bg-[#155EEF] text-white p-6 rounded-xl border border-[#155EEF]">
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center shrink-0 text-white">
                            <i data-feather="zap" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-widest mb-1">Need Urgent Teamwear?</h3>
                            <p class="text-xs text-white/80 leading-relaxed mb-4">
                                WhatsApp our team with your deadline, quantity, and design idea for rapid quotation.
                            </p>
                            <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20have%20an%20urgent%20teamwear%20order." target="_blank" rel="noopener noreferrer" 
                               class="inline-flex items-center gap-2 bg-white text-[#155EEF] px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wider hover:bg-[#F7F7F5] transition-all">
                                WhatsApp Urgent Inquiry
                                <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Clean Inquiry Form (7 cols) -->
            <div class="lg:col-span-7">
                <div class="bg-white border border-[#E8E8E3] rounded-xl p-6 md:p-10 shadow-sm">
                    <div class="mb-8 pb-4 border-b border-[#E8E8E3] flex items-center justify-between">
                        <div>
                            <h2 class="text-lg md:text-xl font-black uppercase tracking-tight text-[#111111]">Send An Inquiry</h2>
                            <p class="text-xs text-[#666666] font-medium mt-1">Fill in the details below and our team will be in touch.</p>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#155EEF] bg-[#155EEF]/10 px-2.5 py-1 rounded-md">
                            Form
                        </span>
                    </div>

                    @if(session('success'))
                        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg flex items-start gap-3 mb-6 animate-fade-in">
                            <i data-feather="check-circle" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="font-bold text-xs uppercase tracking-wider text-emerald-800 mb-0.5">Message Sent Successfully</h4>
                                <p class="text-xs leading-relaxed">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('pages.contact-us.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Name & Email (2 Cols on desktop) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-[#111111] text-[10px] font-black uppercase tracking-widest mb-1.5">
                                    Your Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                    class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-lg px-4 py-3 text-xs font-semibold focus:outline-none focus:border-[#155EEF] focus:bg-white focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999]"
                                    placeholder="e.g. John Doe">
                                @error('name')
                                    <p class="text-rose-600 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-[#111111] text-[10px] font-black uppercase tracking-widest mb-1.5">
                                    Email Address <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                    class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-lg px-4 py-3 text-xs font-semibold focus:outline-none focus:border-[#155EEF] focus:bg-white focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999]"
                                    placeholder="e.g. john@example.com">
                                @error('email')
                                    <p class="text-rose-600 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-[#111111] text-[10px] font-black uppercase tracking-widest mb-1.5">
                                Subject / Topic
                            </label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-lg px-4 py-3 text-xs font-semibold focus:outline-none focus:border-[#155EEF] focus:bg-white focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999]"
                                placeholder="e.g. Custom Jerseys for Football Club (30 pcs)">
                            @error('subject')
                                <p class="text-rose-600 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-[#111111] text-[10px] font-black uppercase tracking-widest mb-1.5">
                                Message Details <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="message" name="message" required rows="5"
                                class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-lg p-4 text-xs font-semibold focus:outline-none focus:border-[#155EEF] focus:bg-white focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999] resize-none"
                                placeholder="Please let us know how we can help you (quantities, deadline, design details, etc.)...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-rose-600 text-[10px] font-bold uppercase tracking-wider mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#155EEF] text-white font-black uppercase tracking-widest px-8 py-3.5 rounded-lg hover:bg-[#0d46b3] transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs shadow-md">
                                <span>Submit Message</span>
                                <i data-feather="send" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
