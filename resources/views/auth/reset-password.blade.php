<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="text-4xl mb-3">✨</div>
        <h2 class="text-2xl font-bold text-gray-900">Set New Password</h2>
        <p class="mt-2 text-sm text-gray-600">
            Link verified for <strong class="text-purple-600">{{ $email }}</strong>. Create a strong new password.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Email (hidden) -->
        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                🔑 New Password
            </label>
            <x-text-input 
                id="password" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password" 
                autofocus
                placeholder="••••••••" 
            />
            <p class="mt-1 text-xs text-gray-500">At least 8 characters with uppercase, lowercase, and numbers</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                🔐 Confirm New Password
            </label>
            <x-text-input 
                id="password_confirmation" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" 
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-lg hover:shadow-xl mt-6">
            🔓 Reset Password
        </button>
    </form>

    <!-- Info Box -->
    <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-xs text-blue-700">
            <span class="font-semibold">💡 Tip:</span> Choose a strong password with at least one uppercase letter, one number, and one special character.
        </p>
    </div>
</x-guest-layout>
