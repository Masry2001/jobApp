@props([
    'href' => '#',
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'bg-gray-800/50 text-gray-300 hover:bg-gray-700/70 hover:text-white border border-gray-700 hover:border-gray-600',
        'primary' => 'bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/30 border-0',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 border-0 shadow-lg shadow-emerald-500/20',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 border-0 shadow-lg shadow-red-500/20',
    ];

    $variantClasses = $variants[$variant] ?? $variants['default'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-200 hover:scale-105 ' . $variantClasses])}}>
    {{ $slot }}
</a>