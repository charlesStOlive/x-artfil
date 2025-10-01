<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'X-Artfil') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Styles -->
    @vite(['resources/css/front/theme.css', 'resources/js/front/app.js'])
    @livewireStyles

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" x-data="frontApp()">
    <!-- Header -->
    <x-front.header />

    <!-- Main Content -->
    <main class="min-h-screen">
        @isset($hero)
            {{ $hero }}
        @endisset

        @if (session('message'))
            <div x-data="{ show: true }" x-show="show" x-transition class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg m-4" role="alert">
                <div class="flex justify-between items-center">
                    <span>{{ session('message') }}</span>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-front.footer />

    @stack('scripts')
    @livewireScripts
</body>
</html>