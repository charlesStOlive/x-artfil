<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="{{ $metaDescription ?? 'Découvrez l\'art-thérapie avec notre praticien certifié.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'art-thérapie, thérapie, créativité' }}">
    
    <meta property="og:title" content="{{ $title ?? 'Art-Thérapie - Découvrez votre créativité' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Découvrez l\'art-thérapie avec notre praticien certifié.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <title>{{ $title }} - {{ config('app.name') }}</title>

    {{-- Preconnect pour les performances --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Styles -->
    @vite(['resources/css/front/front.css', 'resources/js/front/front.js'])
    @livewireStyles

    @stack('styles')
</head>
<body class="font-sans antialiased bg-white" x-data="frontApp()">
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="mb-8">
        {{ $slot }}
    </main>

    <!-- Contact Form (conditionnel) -->
    @if(isset($hasForm) && $hasForm)
        @include('partials.form')
    @endif

    <!-- Footer -->
    @include('partials.footer')

    @stack('scripts')
    @livewireScripts
</body>
</html>