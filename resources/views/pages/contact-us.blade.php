@extends('layouts.public')

@section('title', 'Contact Us - Maxumax')

@section('content')
<div class="bg-black min-h-screen pt-32 pb-40 px-6 overflow-hidden relative flex items-center justify-center">
    <!-- Background Accents -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[60vh] bg-gradient-to-b from-blue-600/10 via-transparent to-transparent pointer-events-none"></div>

    <div class="w-full max-w-xl relative z-10">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in text-focus-in">
            <span class="text-blue-500 font-black uppercase tracking-[0.4em] text-[10px] mb-4 inline-block">Get In Touch</span>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase italic leading-none mb-4">
                Contact <span class="text-blue-500">Us.</span>
            </h1>
            <p class="text-white/40 font-black uppercase tracking-widest text-[10px] leading-relaxed max-w-md mx-auto">
                Send us your inquiry and our team will get back to you as soon as possible.
            </p>
        </div>

        <!-- Inquiry Form -->
        <div class="bg-[#111111] border border-white/5 rounded-[2.5rem] p-8 md:p-12 shadow-3xl animate-fade-in-up">
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-6 rounded-2xl text-center mb-6">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-500/20">
                        <i data-feather="check" class="text-emerald-500 w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-lg text-white mb-2">Message Sent!</h3>
                    <p class="text-sm text-white/60 leading-relaxed">Thank you. Your inquiry has been received and is being processed by our team.</p>
                </div>
            @endif

            <form action="{{ route('pages.contact-us.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name Input -->
                <div class="space-y-2">
                    <label for="name" class="block text-white/50 text-[10px] font-black uppercase tracking-widest">Your Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}"
                        class="w-full bg-black/40 text-white border border-white/5 rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-blue-500/40 focus:ring-1 focus:ring-blue-500/40 transition-all placeholder-white/20"
                        placeholder="John Doe">
                    @error('name')
                        <p class="text-rose-500 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="space-y-2">
                    <label for="email" class="block text-white/50 text-[10px] font-black uppercase tracking-widest">Email Address</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}"
                        class="w-full bg-black/40 text-white border border-white/5 rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-blue-500/40 focus:ring-1 focus:ring-blue-500/40 transition-all placeholder-white/20"
                        placeholder="john@example.com">
                    @error('email')
                        <p class="text-rose-500 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Input -->
                <div class="space-y-2">
                    <label for="subject" class="block text-white/50 text-[10px] font-black uppercase tracking-widest">Subject (Optional)</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                        class="w-full bg-black/40 text-white border border-white/5 rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-blue-500/40 focus:ring-1 focus:ring-blue-500/40 transition-all placeholder-white/20"
                        placeholder="e.g. Custom Jerseys Inquiry">
                    @error('subject')
                        <p class="text-rose-500 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Input -->
                <div class="space-y-2">
                    <label for="message" class="block text-white/50 text-[10px] font-black uppercase tracking-widest">Your Message</label>
                    <textarea id="message" name="message" required rows="5"
                        class="w-full bg-black/40 text-white border border-white/5 rounded-2xl px-5 py-4 text-sm font-medium focus:outline-none focus:border-blue-500/40 focus:ring-1 focus:ring-blue-500/40 transition-all placeholder-white/20 resize-none"
                        placeholder="Describe your inquiry details here..."></textarea>
                    @error('message')
                        <p class="text-rose-500 text-[11px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full inline-flex items-center justify-center bg-white text-black font-black uppercase tracking-wider py-4 rounded-full hover:bg-slate-200 transition-all duration-300 hover:scale-[1.02] active:scale-95 text-xs">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
