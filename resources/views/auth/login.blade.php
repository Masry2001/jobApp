<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-gray-400 text-sm">Sign in to continue to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1.5" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1.5" type="password" name="password" required
                autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox"
                    class="w-4 h-4 rounded border-gray-600 bg-gray-900/50 text-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-0 transition-colors cursor-pointer"
                    name="remember">
                <span
                    class="ms-2 text-sm text-gray-400 group-hover:text-gray-300 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-400 hover:text-white transition-colors duration-200 underline decoration-gray-600 hover:decoration-white"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
            <a class="text-sm text-gray-400 hover:text-white transition-colors duration-200 underline decoration-gray-600 hover:decoration-white"
                href="{{ route('register') }}">
                {{ __('Don\'t have an account?') }}
            </a>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>