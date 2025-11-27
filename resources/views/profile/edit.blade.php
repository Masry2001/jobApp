<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information Section -->
            <div class="p-6 sm:p-8 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm shadow-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Section -->
            <div class="p-6 sm:p-8 bg-gray-800/50 border border-gray-700 rounded-xl backdrop-blur-sm shadow-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="p-6 sm:p-8 bg-gray-800/50 border border-red-900/30 rounded-xl backdrop-blur-sm shadow-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>