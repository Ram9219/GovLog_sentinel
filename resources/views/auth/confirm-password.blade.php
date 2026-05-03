<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="text-4xl mb-3">🔐</div>
        <h2 class="text-2xl font-bold text-gray-900">Confirm Password</h2>
        <p class="mt-2 text-sm text-gray-600">
            This is a secure area. Please confirm your password to continue.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                🔑 Password
            </label>
            <x-text-input 
                id="password" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-lg hover:shadow-xl">
            ✓ Confirm & Continue
        </button>
    </form>
</x-guest-layout>
