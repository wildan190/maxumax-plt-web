@extends('layouts.public')

@section('title', 'Maxumax - Elevated Sports Performance')

@section('content')

    @include('partials.home.hero')
    @include('partials.home.shop-by-sport')
    @include('partials.home.shop-by-product')
    @include('partials.home.custom-process')
    @include('partials.home.trust-boxes')
    @include('partials.home.featured-collections')
    @include('partials.home.new-arrivals')
    @include('partials.home.ways-to-shop')
    @include('partials.home.brand-story')
    @include('partials.home.trusted-projects')
    @include('partials.home.customizable-items')
    @include('partials.home.whatsapp-cta')

@endsection
