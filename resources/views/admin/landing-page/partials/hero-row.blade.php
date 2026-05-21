@php
    /** @var int|string $index */
    /** @var \App\Models\LandingHeroSlide|null $slide */
    $buttons = $slide?->buttons ?? [];
@endphp
<div data-hero-row class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mb-6 relative group">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm">{{ (int)$index + 1 }}</span>
            <h3 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Hero Slide</h3>
        </div>
        <button type="button" onclick="removeHeroRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-xs uppercase tracking-widest transition-colors">
            Remove Slide
        </button>
    </div>

    @if ($slide)
        <input type="hidden" name="hero[{{ $index }}][id]" value="{{ $slide->id }}">
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-1.5">Slide Title</label>
                <input type="text" name="hero[{{ $index }}][title]" value="{{ old('hero.'.$index.'.title', $slide?->title) }}"
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                @error('hero.'.$index.'.title')
                    <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-1.5">Description Body</label>
                <textarea name="hero[{{ $index }}][body]" rows="3"
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">{{ old('hero.'.$index.'.body', $slide?->body) }}</textarea>
                @error('hero.'.$index.'.body')
                    <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-1.5">Background Image (.webp, max 2MB)</label>
            <div class="relative group/img aspect-[16/7] bg-white border-2 border-dashed border-slate-200 rounded-2xl overflow-hidden flex flex-col items-center justify-center transition-all hover:border-indigo-300">
                @if ($slide?->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($slide->image_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slide->image_path) }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-white text-[10px] font-black uppercase tracking-widest">Change Image</span>
                    </div>
                @else
                    <div class="flex flex-col items-center text-slate-400">
                        <i data-feather="image" class="w-8 h-8 mb-2"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Upload .webp</span>
                    </div>
                @endif
                <input type="file" name="hero[{{ $index }}][image]" accept=".webp,image/webp"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
            @error('hero.'.$index.'.image')
                <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 p-5">
        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4">Call to Action Buttons</h4>
        <div class="space-y-3">
            @for ($b = 0; $b < 3; $b++)
                @php $btn = $buttons[$b] ?? []; @endphp
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Button {{ $b + 1 }} Label</label>
                        <input type="text" name="hero[{{ $index }}][buttons][{{ $b }}][label]" value="{{ old('hero.'.$index.'.buttons.'.$b.'.label', $btn['label'] ?? '') }}"
                            placeholder="e.g. Shop Now"
                            class="w-full bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 text-xs font-bold focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">URL / Link</label>
                        <input type="text" name="hero[{{ $index }}][buttons][{{ $b }}][url]" value="{{ old('hero.'.$index.'.buttons.'.$b.'.url', $btn['url'] ?? '') }}"
                            placeholder="/products or https://..."
                            class="w-full bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 text-xs font-bold focus:outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div class="md:col-span-2 flex items-center justify-center pb-1.5">
                        <label class="flex items-center gap-2 cursor-pointer group/check">
                            <input type="hidden" name="hero[{{ $index }}][buttons][{{ $b }}][primary]" value="0">
                            <input type="checkbox" name="hero[{{ $index }}][buttons][{{ $b }}][primary]" value="1" @checked(old('hero.'.$index.'.buttons.'.$b.'.primary', !empty($btn['primary'])))
                                class="w-4 h-4 rounded border-slate-200 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            <span class="text-[10px] font-black uppercase text-slate-500 group-hover/check:text-indigo-600 transition-colors">Primary</span>
                        </label>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
