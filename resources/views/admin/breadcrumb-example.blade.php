<!-- Example Usage of Breadcrumbs -->
<!-- Letakkan di bagian atas view sebelum @extends -->

<?php
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Profil', 'url' => route('profile.show')],
    ['label' => 'Edit', 'url' => '#'],
];
?>

@extends('layouts.app')

@section('title', 'Edit Profil')

@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Perbarui informasi profil Anda')

@section('content')
<!-- Your content here -->
@endsection

<!-- 
ATAU menggunakan helper:

<?php page_breadcrumbs([
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Settings', 'url' => route('settings')],
    ['label' => 'Security', 'url' => '#'],
]); ?>
-->
