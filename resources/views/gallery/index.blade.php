@extends('layouts.public')

@section('title', 'Gallery - Maxumax')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Our Gallery</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore our collection of jerseys and moments.</p>
        </div>

        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($galleries as $gallery)
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-lg transition-transform hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative h-64 overflow-hidden group">
                            <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                <div>
                                    <h3 class="text-white font-bold text-xl mb-1">{{ $gallery->title }}</h3>
                                    @if($gallery->description)
                                        <p class="text-gray-200 text-sm">{{ $gallery->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $galleries->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-gray-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 text-lg">No images available in the gallery yet.</p>
            </div>
        @endif
    </div>
@endsection