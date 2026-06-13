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
    "email": "maxumax.my@gmail.com",
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
<div class="bg-white min-h-screen pt-32 pb-40 px-6 overflow-hidden relative flex items-center justify-center">
    <!-- Background Accents -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[60vh] bg-gradient-to-b from-[#155EEF]/10 via-transparent to-transparent pointer-events-none"></div>

    <div class="w-full max-w-xl relative z-10">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in text-focus-in">
            <span class="text-[#155EEF] font-black uppercase tracking-[0.4em] text-[10px] mb-4 inline-block">Get In Touch</span>
            <h1 class="text-4xl md:text-6xl font-black text-[#111111] tracking-tighter uppercase italic leading-none mb-4">
                Contact <span class="text-[#155EEF]">Us.</span>
            </h1>
            <p class="text-[#666666] font-black uppercase tracking-widest text-[10px] leading-relaxed max-w-md mx-auto">
                Send us your inquiry and our team will get back to you as soon as possible.
            </p>
        </div>

        <!-- Inquiry Form -->
        <div class="bg-white border border-[#E8E8E3] rounded-[2.5rem] p-8 md:p-12 shadow-3xl animate-fade-in-up">
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 p-6 rounded-2xl text-center mb-6">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
                        <i data-feather="check" class="text-emerald-600 w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-lg text-[#111111] mb-2">Message Sent!</h3>
                    <p class="text-sm text-[#666666] leading-relaxed">Thank you. Your inquiry has been received and is being processed by our team.</p>
                </div>
            @endif

            <form action="{{ route('pages.contact-us.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name Input -->
                <div class="space-y-2">
                    <label for="name" class="block text-[#666666] text-[10px] font-black uppercase tracking-widest">Your Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-[#155EEF] focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999]"
                        placeholder="John Doe">
                    @error('name')
                        <p class="text-red-600 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="space-y-2">
                    <label for="email" class="block text-[#666666] text-[10px] font-black uppercase tracking-widest">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-[#155EEF] focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999]"
                        placeholder="john@example.com">
                    @error('email')
                        <p class="text-red-600 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Input -->
                <div class="space-y-2">
                    <label for="subject" class="block text-[#666666] text-[10px] font-black uppercase tracking-widest">Subject (Optional)</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                        class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-[#155EEF] focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999]"
                        placeholder="e.g. Custom Jerseys Inquiry">
                    @error('subject')
                        <p class="text-red-600 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Input -->
                <div class="space-y-2">
                    <label for="message" class="block text-[#666666] text-[10px] font-black uppercase tracking-widest">Your Message</label>
                    <textarea id="message" name="message" required rows="5"
                        class="w-full bg-[#F7F7F5] text-[#111111] border border-[#E8E8E3] rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-[#155EEF] focus:ring-1 focus:ring-[#155EEF] transition-all placeholder-[#999999] resize-none"
                        placeholder="Describe your inquiry details here..."></textarea>
                    @error('message')
                        <p class="text-red-600 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full inline-flex items-center justify-center bg-[#155EEF] text-white font-black uppercase tracking-wider py-4 rounded-full hover:bg-[#0D4BC3] transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
