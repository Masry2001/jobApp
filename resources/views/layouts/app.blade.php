<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Alpine.js Cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-black text-white">
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-900">
        <!-- Subtle background effects -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-500/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-gray-900/50 backdrop-blur-sm border-b border-gray-800 relative z-10">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="relative z-10">

            @if (session('success'))
                <x-flash-message type="success" :message="session('success')" />
            @endif

            @if (session('error'))
                <x-flash-message type="error" :message="session('error')" />
            @endif

            {{ $slot }}
        </main>
    </div>
</body>

</html>