<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="text-4xl mb-3">🔑</div>
        <h2 class="text-2xl font-bold text-gray-900">Forgot Password?</h2>
        <p class="mt-2 text-sm text-gray-600">
            No problem. Enter your email address and we'll send you a reset link.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                📧 Email Address
            </label>
            <x-text-input 
                id="email" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                placeholder="your.email@government.in"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-lg hover:shadow-xl">
            📨 Send Reset Link
        </button>

        <!-- Back to Login -->
        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium transition">
                ← Back to Sign In
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div class="mt-6 p-3 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-xs text-green-700">
            <span class="font-semibold">✓ Secure:</span> Check your email for the password reset link. It will expire in 60 minutes.
        </p>
    </div>
</x-guest-layout>
