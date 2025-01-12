<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-indigo-100">
        <div class="w-full max-w-md px-6 py-8 bg-white shadow-md rounded-lg">
            <!-- Logo Section -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/mc.png') }}" alt="Your Logo" class="h-16 w-16">
            </div>

            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Log in to Your Account</h2>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-sm font-semibold text-gray-600" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <!-- Password Field -->
                <div>
                    <x-label for="password" value="{{ __('Password') }}" class="text-sm font-semibold text-gray-600" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Forgot Password and Login Button -->
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-blue-500 hover:text-blue-700" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-button class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>

            <!-- Registration Link -->
            <div class="mt-6 text-center">
                <span class="text-sm text-gray-600">Don't have an account? </span>
                <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 underline text-sm">
                    {{ __('Register') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
