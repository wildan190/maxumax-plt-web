@extends('layouts.app')

@section('page-title', 'Reorder Products')
@section('page-subtitle', 'Drag and drop products to rearrange their storefront order.')

@section('content')
<div class="space-y-6" x-data="reorderList()">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Storefront Arrangement</h3>
            <p class="text-xs text-slate-500 leading-relaxed mt-1">Group products belonging to the same category or football team together for a cleaner catalog experience.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 transition-all text-center flex-1 sm:flex-none">
                Back to List
            </a>
            <button type="button" @click="saveOrder()" :disabled="saving" class="inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 disabled:opacity-50 transition-all shadow-lg shadow-indigo-600/20 gap-2 flex-1 sm:flex-none">
                <span x-show="!saving" class="flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Save Ordering
                </span>
                <span x-show="saving" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </div>

    <!-- Category Filters -->
    <div class="flex flex-wrap items-center gap-2">
        <button type="button" @click="filterCategory = 'all'" :class="filterCategory === 'all' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 text-xs font-bold rounded-full transition-all">
            All Categories
        </button>
        @foreach($products->pluck('category')->unique() as $cat)
            @if($cat)
                <button type="button" @click="filterCategory = '{{ $cat }}'" :class="filterCategory === '{{ $cat }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'" class="px-4 py-2 text-xs font-bold rounded-full transition-all">
                    {{ $cat }}
                </button>
            @endif
        @endforeach
    </div>

    <!-- Drag & Drop Grid -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="draggable-grid">
            <template x-for="(item, index) in items" :key="item.id">
                <div x-show="filterCategory === 'all' || item.category === filterCategory" 
                     :data-id="item.id"
                     draggable="true"
                     @dragstart="dragStart($event, index)"
                     @dragover.prevent="dragOver($event, index)"
                     @drop="drop($event, index)"
                     @dragend="dragEnd($event)"
                     :class="{'opacity-50 scale-95 border-indigo-300 bg-indigo-50/30': draggingIndex === index}"
                     class="flex items-center gap-4 p-4 border border-slate-150 rounded-2xl cursor-grab active:cursor-grabbing hover:border-indigo-200 hover:bg-slate-50/50 transition-all group relative">
                    
                    <!-- Handle & Position -->
                    <div class="flex flex-col items-center justify-center text-slate-400 gap-1 select-none">
                        <i data-feather="menu" class="w-4 h-4 opacity-50 group-hover:opacity-100 group-hover:text-indigo-500 transition-all"></i>
                        <span class="text-[10px] font-bold text-slate-400/70" x-text="index + 1"></span>
                    </div>

                    <!-- Product Image -->
                    <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 bg-slate-50">
                        <img :src="item.image_path ? '/storage/' + item.image_path : '/placeholder.jpg'" class="w-full h-full object-cover" alt="" />
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-900 truncate" x-text="item.name"></p>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-bold text-slate-600" x-text="item.category"></span>
                            <template x-if="item.collection">
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-[10px] font-bold text-indigo-600" x-text="item.collection"></span>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function reorderList() {
        return {
            items: @json($products),
            filterCategory: 'all',
            draggingIndex: null,
            saving: false,

            dragStart(e, index) {
                this.draggingIndex = index;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', index);
            },

            dragOver(e, index) {
                e.preventDefault();
            },

            drop(e, index) {
                e.preventDefault();
                const fromIndex = parseInt(e.dataTransfer.getData('text/plain'));
                if (fromIndex !== index) {
                    const movedItem = this.items.splice(fromIndex, 1)[0];
                    this.items.splice(index, 0, movedItem);
                    // Re-initialize feather icons for newly rendered dynamic items
                    this.$nextTick(() => {
                        if (window.feather) feather.replace();
                    });
                }
            },

            dragEnd(e) {
                this.draggingIndex = null;
            },

            async saveOrder() {
                this.saving = true;
                const orderPayload = this.items.map(item => item.id);

                try {
                    const response = await fetch("{{ route('admin.products.reorder.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSR-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ order: orderPayload })
                    });

                    if (response.ok) {
                        // Flash success message
                        window.location.href = "{{ route('admin.products.index') }}?status=Product+ordering+updated+successfully";
                    } else {
                        alert('Failed to save ordering. Please try again.');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Network error. Failed to save ordering.');
                } finally {
                    this.saving = false;
                }
            }
        }
    }
</script>
@endsection
