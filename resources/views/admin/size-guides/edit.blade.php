@extends('layouts.app')

@section('page-title', 'Edit Size Guide')

@section('content')
    <div style="background: white; border-radius: 0.75rem; border: 1px solid #e5e7eb; padding: 2rem; max-width: 1000px;">
        <form action="{{ route('admin.size-guides.update', $sizeGuide) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Name</label>
                <input type="text" name="name" value="{{ old('name', $sizeGuide->name) }}" required
                       style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Image Chart (.webp or .png)</label>
                @if($sizeGuide->image_path)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $sizeGuide->image_path) }}" style="max-width: 200px; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                    </div>
                @endif
                <input type="file" name="image" accept=".webp,.png"
                       style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 1rem;">Table Data</label>
                
                <div id="table-builder" x-data="tableBuilder()">
                    <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem; align-items: center;">
                        <button type="button" @click="addColumn()" style="background: #f3f4f6; padding: 0.4rem 0.8rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">+ Add Column</button>
                        <button type="button" @click="addRow()" style="background: #f3f4f6; padding: 0.4rem 0.8rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">+ Add Row</button>
                    </div>

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                            <thead>
                                <tr>
                                    <template x-for="(header, hIndex) in headers" :key="'h-'+hIndex">
                                        <th style="padding: 0.5rem;">
                                            <div style="display: flex; gap: 0.25rem;">
                                                <input type="text" :name="'headers['+hIndex+']'" x-model="headers[hIndex]" 
                                                       style="width: 100%; padding: 0.4rem; border: 1px solid #d1d5db; border-radius: 0.25rem; font-size: 0.8rem;">
                                                <button type="button" @click="removeColumn(hIndex)" style="color: #ef4444; font-size: 1.2rem;">&times;</button>
                                            </div>
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, rIndex) in rows" :key="'r-'+rIndex">
                                    <tr>
                                        <template x-for="(cell, cIndex) in row" :key="'r-'+rIndex+'-c-'+cIndex">
                                            <td style="padding: 0.5rem;">
                                                <input type="text" :name="'rows['+rIndex+']['+cIndex+']'" x-model="rows[rIndex][cIndex]"
                                                       style="width: 100%; padding: 0.4rem; border: 1px solid #f3f4f6; border-radius: 0.25rem; font-size: 0.8rem;">
                                            </td>
                                        </template>
                                        <td style="padding: 0.5rem; width: 40px;">
                                            <button type="button" @click="removeRow(rIndex)" style="color: #ef4444; font-size: 1.2rem;">&times;</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $sizeGuide->is_active ? 'checked' : '' }}
                           style="width: 1.125rem; height: 1.125rem; border-radius: 0.375rem;">
                    <span style="font-weight: 600; color: #374151;">Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; border-top: 1px solid #e5e7eb; pt: 1.5rem;">
                <button type="submit" style="background: #000; color: #fff; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 700; border: none; cursor: pointer;">
                    Update Size Guide
                </button>
                <a href="{{ route('admin.size-guides.index') }}" style="display: inline-block; padding: 0.75rem 2rem; border-radius: 0.5rem; font-weight: 600; color: #4b5563; text-decoration: none; background: #f3f4f6;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function tableBuilder() {
        return {
            headers: {!! json_encode($sizeGuide->data['headers'] ?? ['Size', 'Chest Width', 'Body Length']) !!},
            rows: {!! json_encode($sizeGuide->data['rows'] ?? [['XS', '', ''], ['S', '', '']]) !!},
            addColumn() {
                this.headers.push('New Column');
                this.rows.forEach(row => row.push(''));
            },
            removeColumn(index) {
                if (this.headers.length <= 1) return;
                this.headers.splice(index, 1);
                this.rows.forEach(row => row.splice(index, 1));
            },
            addRow() {
                const newRow = new Array(this.headers.length).fill('');
                this.rows.push(newRow);
            },
            removeRow(index) {
                if (this.rows.length <= 1) return;
                this.rows.splice(index, 1);
            }
        }
    }
</script>
@endpush
