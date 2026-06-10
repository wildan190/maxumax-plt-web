@extends('layouts.public')

@section('title', 'Size Guide - Maxumax')

@section('content')
<main class="min-h-screen bg-white text-[#111111] pt-24 pb-16">
    <!-- Header Section -->
    <section class="max-w-4xl mx-auto px-6 text-center mb-16">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black uppercase tracking-tight mb-6">MADE TO FIT, <span class="text-[#666666]">BUILT FOR PERFORMANCE</span></h1>
        <p class="text-lg md:text-xl text-[#666666] max-w-2xl mx-auto">
            Find your perfect size using our detailed measurement guides.
        </p>
    </section>

    @if($sizeGuides->isNotEmpty())
    <!-- Size Guide Layout -->
    <section class="max-w-[1600px] mx-auto px-6 mb-24" x-data="{ 
        activePdf: '{{ asset('storage/' . $sizeGuides->first()->image_path) }}',
        activeName: '{{ $sizeGuides->first()->name }}'
    }">
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            <!-- Left Side: List (Narrower) -->
            <div class="w-full lg:w-[22%] space-y-3">
                <div class="bg-[#F7F7F5] border border-[#E8E8E3] rounded-2xl p-3 sticky top-24">
                    <p class="text-[#666666] text-[9px] font-black uppercase tracking-[0.2em] mb-3 px-2">Select Category</p>
                    <div class="flex flex-col gap-1.5">
                        @foreach($sizeGuides as $guide)
                            <button 
                                @click="activePdf = '{{ asset('storage/' . $guide->image_path) }}'; activeName = '{{ $guide->name }}'"
                                :class="activeName === '{{ $guide->name }}' ? 'bg-[#155EEF] text-white shadow-lg shadow-[#155EEF]/20' : 'bg-white text-[#666666] hover:bg-[#F7F7F5] hover:text-[#111111]'"
                                class="flex items-center justify-between px-3 py-3 rounded-xl transition-all duration-300 text-left group"
                            >
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <i data-feather="file-text" class="w-3.5 h-3.5 flex-shrink-0 opacity-50 group-hover:opacity-100 transition-opacity"></i>
                                    <span class="text-[11px] font-black uppercase tracking-tight truncate">{{ $guide->name }}</span>
                                </div>
                                <i data-feather="chevron-right" class="w-3.5 h-3.5 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-all transform translate-x-[-5px] group-hover:translate-x-0" :class="activeName === '{{ $guide->name }}' ? 'opacity-100 translate-x-0' : ''"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Preview (Much Wider) -->
            <div class="w-full lg:w-[78%] h-[85vh] sticky top-24">
                <div class="bg-white border border-[#E8E8E3] rounded-3xl overflow-hidden h-full flex flex-col">
                    <!-- Preview Header -->
                    <div class="bg-[#F7F7F5] border-b border-[#E8E8E3] px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#155EEF] rounded-lg flex items-center justify-center text-white">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-[#111111] font-black uppercase tracking-tight text-sm" x-text="activeName + '.pdf'"></h3>
                        </div>
                        <a :href="activePdf" target="_blank" class="text-[#666666] hover:text-[#111111] transition-colors">
                            <i data-feather="external-link" class="w-4 h-4"></i>
                        </a>
                    </div>
                    
                    <!-- PDF Viewer -->
                    <div class="flex-1 bg-[#F7F7F5] relative">
                        <iframe :src="activePdf + '#toolbar=0&navpanes=0&scrollbar=0'" class="w-full h-full border-none" style="filter: invert(0.05) brightness(1.05) !important;" x-show="activePdf" x-transition></iframe>
                        
                        <!-- Loading State / Fallback -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none" x-show="!activePdf">
                            <p class="text-[#E8E8E3] font-black uppercase tracking-widest italic">Loading Preview...</p>
                        </div>
                    </div>

                    <!-- Preview Footer -->
                    <div class="p-4 border-t border-[#E8E8E3] bg-white flex justify-center">
                        <a :href="activePdf" download class="inline-flex items-center gap-2 bg-[#155EEF] text-white px-8 py-3 rounded-xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-[#0D4BC3] transition-all active:scale-95">
                            Download Document
                            <i data-feather="download" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="max-w-4xl mx-auto px-6 text-center py-20">
        <p class="text-[#666666] italic">Size guides are currently being updated. Please check back later.</p>
    </section>
    @endif

    <!-- Closing & CTA Section -->
    <section class="max-w-3xl mx-auto px-6 text-center border-t border-[#E8E8E3] pt-16">
        <h3 class="text-2xl font-bold mb-4 text-[#111111]">Still unsure about sizing?</h3>
        <p class="text-[#666666] mb-8 max-w-xl mx-auto">
            Our team is here to help you choose with confidence so every piece fits perfectly. Reach out to us for personalized guidance.
        </p>
        <a href="https://wa.me/60143436496" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center bg-[#155EEF] text-white font-black uppercase tracking-wider px-8 py-4 rounded-full hover:bg-[#0D4BC3] transition-all duration-300 hover:scale-105 active:scale-95">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Contact Maxumax
        </a>
    </section>
</main>
@endsection
