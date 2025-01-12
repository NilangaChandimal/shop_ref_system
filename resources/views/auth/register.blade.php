<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
        <div class="w-full max-w-md px-6 py-8 bg-white shadow-md rounded-lg">
            <!-- Logo Section -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/mc.png') }}" alt="Your Logo" class="h-16 w-16">
            </div>

            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Create an Account</h2>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name Field -->
                <div>
                    <x-label for="name" value="{{ __('Name') }}" class="text-sm font-semibold text-gray-600" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <!-- Email Field -->
                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-sm font-semibold text-gray-600" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <!-- Password Field -->
                <div>
                    <x-label for="password" value="{{ __('Password') }}" class="text-sm font-semibold text-gray-600" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-sm font-semibold text-gray-600" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div>
                        <x-label for="terms">
                            <div class="flex items-start space-x-2">
                                <x-checkbox name="terms" id="terms" required />
                                <span class="text-sm text-gray-600">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a href="'.route('terms.show').'" target="_blank" class="text-blue-500 underline hover:text-blue-700">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a href="'.route('policy.show').'" target="_blank" class="text-blue-500 underline hover:text-blue-700">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </div>
                        </x-label>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:underline">Already registered?</a>
                    <x-button class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md">
                        {{ __('Register') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
