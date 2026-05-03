<x-guest-layout>
    <div class="mb-4 text-center">
        <div class="text-4xl mb-2">🔐</div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Reset Password</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            We've sent a 6-digit OTP to <strong class="text-indigo-600">{{ $email }}</strong>
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-lg text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.verify-otp.submit') }}">
        @csrf

        <!-- OTP Input -->
        <div>
            <x-input-label for="otp" :value="__('Enter OTP Code')" class="text-center" />
            <x-text-input id="otp"
                class="block mt-1 w-full text-center text-2xl tracking-[0.5em] font-mono"
                type="text"
                name="otp"
                maxlength="6"
                placeholder="000000"
                required
                autofocus
                autocomplete="one-time-code"
                inputmode="numeric"
                pattern="[0-9]{6}" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 text-center">
            ⏱ OTP expires in 10 minutes
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3">
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('password.resend-otp') }}">
            @csrf
            <button type="submit" class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200">
                {{ __('Resend OTP') }}
            </button>
        </form>

        <a href="{{ route('password.request') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
            {{ __('← Back') }}
        </a>
    </div>
</x-guest-layout>
