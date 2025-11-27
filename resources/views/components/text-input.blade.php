@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 bg-gray-900/50 border border-gray-700 text-white placeholder-gray-500 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:bg-gray-900/70 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>