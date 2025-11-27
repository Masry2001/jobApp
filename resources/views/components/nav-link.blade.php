@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'inline-flex items-center px-4 py-2 border-b-2 border-blue-500 text-md font-semibold leading-5 text-white bg-gray-800/50 rounded-t-lg focus:outline-none focus:border-blue-400 transition duration-200 ease-in-out'
        : 'inline-flex items-center px-4 py-2 border-b-2 border-transparent text-md font-medium leading-5 text-gray-400 hover:text-white hover:bg-gray-800/30 hover:border-gray-600 rounded-t-lg focus:outline-none focus:text-white focus:bg-gray-800/30 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>