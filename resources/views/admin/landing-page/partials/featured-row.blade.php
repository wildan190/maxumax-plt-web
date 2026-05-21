<div data-featured-row class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-4 relative group transition-all hover:border-slate-300">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-3">
            <span class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-xs">{{ (int)$index + 1 }}</span>
            <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[10px]">Featured Collection</h3>
        </div>
        <button type="button" onclick="removeFeaturedRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-[10px] uppercase tracking-widest transition-colors">
            Remove
        </button>
    </div>

    @if ($item)
        <input type="hidden" name="featured[{{ $index }}][id]" value="{{ $item->id }}">
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Image (.webp)</label>
            <div class="relative aspect-square bg-white border-2 border-dashed border-slate-200 rounded-xl overflow-hidden flex flex-col items-center justify-center transition-all hover:border-indigo-300">
                @if ($item?->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <i data-feather="image" class="w-5 h-5 text-slate-300"></i>
                @endif
                <input type="file" name="featured[{{ $index }}][image]" accept=".webp,image/webp"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
            @error('featured.'.$index.'.image')
                <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-9 grid grid-cols-1 gap-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Label</label>
                <input type="text" name="featured[{{ $index }}][label]" value="{{ old('featured.'.$index.'.label', $item?->label) }}"
                    placeholder="e.g. Official Team Collections"
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                @error('featured.'.$index.'.label')
                    <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Filter (query parameter)</label>
                <input type="text" name="featured[{{ $index }}][filter_param]" value="{{ old('featured.'.$index.'.filter_param', $item?->filter_param) }}"
                    placeholder="e.g. official-team"
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                @error('featured.'.$index.'.filter_param')
                    <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
