@props([
    'type' => 'success',
    'message' => null,
    'timeout' => 3000,
])

@php
    $colors = [
        'success' => 'bg-green-50 border-green-400 text-green-700',
        'error'   => 'bg-red-50 border-red-400 text-red-700',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
        'info'    => 'bg-blue-50 border-blue-400 text-blue-700',
    ];

    $icon = [
        'success' => '✔️',
        'error'   => '❌',
        'warning' => '⚠️',
        'info'    => 'ℹ️',
    ];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-init="setTimeout(() => show = false, {{ $timeout }})"
    class="flex items-center justify-between px-4 py-3 border rounded-lg shadow-sm {{ $colors[$type] }} fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-[90%]"
    role="alert"
>
    <div class="flex items-center space-x-3">
        <span class="text-lg">{{ $icon[$type] }}</span>
        <span class="font-medium text-sm">{{ $message }}</span>
    </div>

    <button @click="show = false" class="text-lg font-bold text-gray-500 hover:text-gray-700">&times;</button>
</div>
