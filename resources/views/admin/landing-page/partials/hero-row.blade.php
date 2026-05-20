@php
    /** @var int|string $index */
    /** @var \App\Models\LandingHeroSlide|null $slide */
    $buttons = $slide?->buttons ?? [];
@endphp
<div data-hero-row style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.25rem; margin-bottom: 1rem; background: #fafafa;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <span style="font-weight: 700; color: #374151;">Slide hero</span>
        <button type="button" onclick="removeHeroRow(this)" style="font-size: 0.8rem; color: #b91c1c; background: none; border: none; cursor: pointer; font-weight: 600;">Hapus baris</button>
    </div>
    @if ($slide)
        <input type="hidden" name="hero[{{ $index }}][id]" value="{{ $slide->id }}">
    @endif
    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Judul</label>
        <input type="text" name="hero[{{ $index }}][title]" value="{{ old('hero.'.$index.'.title', $slide?->title) }}"
            style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
        @error('hero.'.$index.'.title')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Teks</label>
        <textarea name="hero[{{ $index }}][body]" rows="3"
            style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">{{ old('hero.'.$index.'.body', $slide?->body) }}</textarea>
        @error('hero.'.$index.'.body')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
    <div style="margin-bottom: 1rem;">
        <label style="display: block; font-weight: 600; margin-bottom: 0.35rem;">Gambar .webp (maks 2MB)</label>
        @if ($slide?->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($slide->image_path))
            <div style="margin-bottom: 0.5rem;">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slide->image_path) }}" alt="" style="max-height: 120px; border-radius: 0.35rem;">
            </div>
        @endif
        <input type="file" name="hero[{{ $index }}][image]" accept=".webp,image/webp"
            style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background: #fff;">
        @error('hero.'.$index.'.image')
            <p style="color: #b91c1c; font-size: 0.85rem; margin-top: 0.35rem;">{{ $message }}</p>
        @enderror
    </div>
    @for ($b = 0; $b < 3; $b++)
        @php $btn = $buttons[$b] ?? []; @endphp
        <div style="display: grid; grid-template-columns: 1fr 2fr auto; gap: 0.5rem; align-items: end; margin-bottom: 0.5rem;">
            <div>
                <label style="font-size: 0.75rem; color: #6b7280;">Tombol {{ $b + 1 }} — label</label>
                <input type="text" name="hero[{{ $index }}][buttons][{{ $b }}][label]" value="{{ old('hero.'.$index.'.buttons.'.$b.'.label', $btn['label'] ?? '') }}"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
            </div>
            <div>
                <label style="font-size: 0.75rem; color: #6b7280;">URL</label>
                <input type="text" name="hero[{{ $index }}][buttons][{{ $b }}][url]" value="{{ old('hero.'.$index.'.buttons.'.$b.'.url', $btn['url'] ?? '') }}"
                    style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
            </div>
            <label style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; white-space: nowrap;">
                <input type="hidden" name="hero[{{ $index }}][buttons][{{ $b }}][primary]" value="0">
                <input type="checkbox" name="hero[{{ $index }}][buttons][{{ $b }}][primary]" value="1" @checked(old('hero.'.$index.'.buttons.'.$b.'.primary', !empty($btn['primary'])))>
                Primary
            </label>
        </div>
    @endfor
</div>
