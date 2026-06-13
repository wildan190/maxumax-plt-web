<div data-project-row class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-4 relative group transition-all hover:border-slate-300">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-3">
            <span class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-xs">{{ (int)$index + 1 }}</span>
            <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[10px]">Project Item</h3>
        </div>
        <button type="button" onclick="removeProjectRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-[10px] uppercase tracking-widest transition-colors">
            Remove
        </button>
    </div>

    @if ($item)
        <input type="hidden" name="projects[{{ $index }}][id]" value="{{ $item->id }}">
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Cover Image (.webp)</label>
            <div class="relative aspect-square bg-white border-2 border-dashed border-slate-200 rounded-xl overflow-hidden flex flex-col items-center justify-center transition-all hover:border-indigo-300">
                @if ($item?->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image_path))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <i data-feather="image" class="w-5 h-5 text-slate-300"></i>
                @endif
                <input type="file" name="projects[{{ $index }}][image]" accept=".webp,image/webp"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
            @error('projects.'.$index.'.image')
                <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-9 grid grid-cols-1 gap-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Category</label>
                    <select name="projects[{{ $index }}][category]" 
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                        <option value="Futsal" {{ old('projects.'.$index.'.category', $item?->category) == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                        <option value="Football" {{ old('projects.'.$index.'.category', $item?->category) == 'Football' ? 'selected' : '' }}>Football</option>
                        <option value="Corporate" {{ old('projects.'.$index.'.category', $item?->category) == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                    </select>
                    @error('projects.'.$index.'.category')
                        <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Project Title</label>
                    <input type="text" name="projects[{{ $index }}][title]" value="{{ old('projects.'.$index.'.title', $item?->title) }}"
                        placeholder="e.g. Sabah FA"
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                    @error('projects.'.$index.'.title')
                        <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Landing Headline</label>
                    <input type="text" name="projects[{{ $index }}][headline]" value="{{ old('projects.'.$index.'.headline', $item?->headline) }}"
                        placeholder="Catchy headline for the project page"
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Landing Subhead</label>
                    <input type="text" name="projects[{{ $index }}][subhead]" value="{{ old('projects.'.$index.'.subhead', $item?->subhead) }}"
                        placeholder="Supporting text for the headline"
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Short Description (Home Page)</label>
                <textarea name="projects[{{ $index }}][description]" rows="2"
                    placeholder="Brief description of the project..."
                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-semibold focus:outline-none focus:border-indigo-500 transition-all">{{ old('projects.'.$index.'.description', $item?->description) }}</textarea>
                @error('projects.'.$index.'.description')
                    <p class="text-rose-500 text-[9px] font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gallery Section -->
            <div class="mt-4">
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Project Gallery (Attach multiple photos)</label>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    @if($item?->gallery)
                        @foreach($item->gallery as $imgPath)
                            <div class="relative aspect-square rounded-lg overflow-hidden group/img">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($imgPath) }}" class="w-full h-full object-cover">
                                <label class="absolute inset-0 bg-rose-500/80 flex items-center justify-center opacity-0 group-hover/img:opacity-100 cursor-pointer transition-opacity">
                                    <input type="checkbox" name="projects[{{ $index }}][remove_gallery][]" value="{{ $imgPath }}" class="hidden">
                                    <i data-feather="trash-2" class="w-4 h-4 text-white"></i>
                                </label>
                            </div>
                        @endforeach
                    @endif
                    <div class="relative aspect-square bg-slate-100 border-2 border-dashed border-slate-300 rounded-lg flex items-center justify-center hover:bg-slate-200 transition-all">
                        <i data-feather="plus" class="w-5 h-5 text-slate-400"></i>
                        <input type="file" name="projects[{{ $index }}][gallery][]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
                <p class="text-[9px] text-slate-400 mt-2 italic">* Click on existing image to mark for deletion</p>
            </div>
        </div>
    </div>
</div>
