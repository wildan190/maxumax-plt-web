<!-- 4. Custom Teamwear Highlight & 8. Process -->
<section id="custom-process" class="bg-white py-24 md:py-32 px-6 border-y border-[#E8E8E3] overflow-hidden">
    <div style="max-width: 1280px; margin: 0 auto;" class="px-0">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="flex flex-col justify-center">
                <h2 class="text-sm font-black text-[#155EEF] uppercase tracking-[0.3em] mb-4">Make It Yours</h2>
                <h3 class="text-4xl md:text-5xl font-black text-[#111111] uppercase tracking-tight mb-6 leading-tight">Fully Customized Sportswear,<br>Produced Locally</h3>
                <p class="text-[#666666] font-medium text-base md:text-lg mb-10 leading-relaxed">
                    MAXUMAX helps teams create custom sportswear from start to finish. From design mockup, material selection, sublimation printing, logo application, nameset, numbering, sewing, and finishing - our team can support your order through our local production capability in Kota Kinabalu, Sabah.
                </p>
                <a href="https://wa.me/60143436496?text=Hi%20MAXUMAX,%20I%20am%20interested%20to%20make%20custom%20teamwear.%0AProduct:%0AQuantity:%0ADeadline:%0ADesign%20idea:%0ALocation:%0ACan%20you%20help%20me%20with%20quotation?" target="_blank" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-[#155EEF] text-white font-black text-sm md:text-base tracking-widest rounded-full uppercase transition-all duration-300 hover:bg-[#0d46b3] hover:scale-105 active:scale-95 shadow-xl w-fit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    Get Team Quotation
                </a>
            </div>
            <!-- Custom Order Process Steps -->
            <div class="bg-[#F7F7F5] p-8 md:p-12 rounded-lg border border-[#E8E8E3] shadow-md relative">
                <div class="absolute -top-6 right-10 bg-[#155EEF] text-white px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-md">
                    Custom Order Process
                </div>
                <ul class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-[#E8E8E3] before:to-transparent">
                    @php
                        $steps = [
                            'Enquiry & Quotation' => 'Provide product type, quantity, size breakdown, design idea, and deadline.',
                            'Design Mockup' => 'Visual mockup based on team identity and requirements.',
                            'Detail Confirmation' => 'Confirm fabric, cutting, collar, logo method, nameset, and finishing.',
                            'Sampling / Approval' => 'Pre-production sample or final mockup approved before bulk production.',
                            'Production' => 'Production begins after confirmation, deposit, and design approval.',
                            'Quality Checking' => 'Products are checked for printing, stitching, size, logos, and finishing.',
                            'Delivery / Collection' => 'Collect from showroom/office or receive shipment.'
                        ];
                        $stepNum = 1;
                    @endphp
                    @foreach ($steps as $title => $desc)
                    <li class="relative flex items-start gap-6">
                        <div class="w-10 h-10 rounded-full bg-[#155EEF] flex items-center justify-center flex-shrink-0 z-10 border-4 border-[#F7F7F5]">
                            <span class="text-white font-black text-sm">{{ $stepNum++ }}</span>
                        </div>
                        <div class="pt-2">
                            <h4 class="text-[#111111] font-bold text-sm md:text-base mb-1 uppercase tracking-wider">{{ $title }}</h4>
                            <p class="text-[#666666] text-xs md:text-sm">{{ $desc }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
