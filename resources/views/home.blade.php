@extends('layouts.public')

@section('title', 'Maxumax - Born in Sabah. Built for Performance.')

@section('content')

    {{-- 1. Hero --}}
    @include('partials.home.hero')

    {{-- 4. Product Highlights --}}
    @include('partials.home.new-arrivals')

    {{-- 3. Featured Collections --}}
    @include('partials.home.shop-by-sport')

    {{-- 5. Trusted Projects --}}
    @include('partials.home.trusted-projects')

    {{-- 6. Why Choose Maxumax --}}
    @include('partials.home.why-choose')

    {{-- 7. Final CTA --}}
    @include('partials.home.whatsapp-cta')

@endsection
