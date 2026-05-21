<div data-project-row style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 1rem; background: #fafafa;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <span style="font-weight: 700; color: #374151;">Item trusted project</span>
        <button type="button" onclick="removeProjectRow(this)" style="font-size: 0.8rem; color: #b91c1c; background: none; border: none; cursor: pointer; font-weight: 600;">Hapus baris</button>
    </div>
    @if ($item)
        <input type="hidden" name="projects[{{ $index }}][id]" value="{{ $item->id }}">
    @endif
    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Category</label>
        <input type="text" name="projects[{{ $index }}][category]" value="{{ old('projects.'.$index.'.category', $item?->category) }}"
            style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
        @error('projects.'.$index.'.category')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Title</label>
        <input type="text" name="projects[{{ $index }}][title]" value="{{ old('projects.'.$index.'.title', $item?->title) }}"
            style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
        @error('projects.'.$index.'.title')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Description</label>
        <textarea name="projects[{{ $index }}][description]" rows="2"
            style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">{{ old('projects.'.$index.'.description', $item?->description) }}</textarea>
        @error('projects.'.$index.'.description')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Gambar .webp (maks 2MB)</label>
        @if ($item?->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image_path))
            <div style="margin-bottom: 0.5rem;">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" alt="" style="max-height: 100px; border-radius: 0.35rem;">
            </div>
        @endif
        <input type="file" name="projects[{{ $index }}][image]" accept=".webp,image/webp"
            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background: #fff;">
        @error('projects.'.$index.'.image')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
</div>
