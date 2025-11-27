<x-main-layout title="Shaghalni">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8 sm:py-12">

        <!-- Brand Badge with pulse animation -->
        <div x-data="{show: false}" x-init="setTimeout(() => show = true, 600)" class="mb-6 sm:mb-8">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="text-center">
                <span
                    class="inline-block px-4 sm:px-6 py-2 bg-gradient-to-r from-blue-500/20 to-purple-500/20 border border-blue-400/30 rounded-full backdrop-blur-sm hover:from-blue-500/30 hover:to-purple-500/30 transition-all duration-300">
                    <h4
                        class="text-xs sm:text-sm font-bold bg-gradient-to-r from-blue-300 to-purple-300 bg-clip-text text-transparent">
                        ✨ Shaghalni
                    </h4>
                </span>
            </div>
        </div>

        <!-- Main Heading with gradient text -->
        <div x-data="{show: false}" x-init="setTimeout(() => show = true, 600)" class="mb-4 sm:mb-6 max-w-5xl mx-auto">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-bold text-center leading-tight px-4 bg-gradient-to-r from-gray-600 via-gray-400 to-gray-200 bg-clip-text text-transparent">
                    Find your dream job
                </h1>
            </div>
        </div>

        <!-- Subtitle/Tagline -->
        <div x-data="{show: false}" x-init="setTimeout(() => show = true, 600)"
            class="mb-10 sm:mb-12 max-w-2xl mx-auto">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <p class="text-base sm:text-lg md:text-xl text-gray-400 text-center px-4 leading-relaxed">
                    Connect with top employers and unlock thousands of opportunities tailored just for you
                </p>
            </div>
        </div>

        <!-- CTA Buttons with enhanced styling -->
        <div x-data="{show: false}" x-init="setTimeout(() => show = true, 600)" class="w-full max-w-md mx-auto mb-12">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="flex flex-col sm:flex-row gap-4 sm:gap-5 justify-center items-stretch sm:items-center px-4">
                <a href="{{ route('login') }}"
                    class="group relative w-full sm:w-auto text-center px-8 py-3.5 sm:py-4 rounded-lg font-semibold text-base sm:text-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/50 hover:scale-105 transition-all duration-300 overflow-hidden">
                    <span class="relative z-10">Login</span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </a>
                <a href="{{ route('register') }}"
                    class="group w-full sm:w-auto text-center px-8 py-3.5 sm:py-4 rounded-lg font-semibold text-base sm:text-lg border-2 border-gray-600 text-gray-200 hover:bg-white/5 hover:border-gray-400 hover:scale-105 transition-all duration-300 backdrop-blur-sm">
                    Register
                </a>
            </div>
        </div>


    </div>

</x-main-layout>