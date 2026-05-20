<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
        <p class="text-gray-600 text-sm">Sign in to your government account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                 Email Address
            </label>
            <x-text-input 
                id="email" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="your.email@government.in" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                 Password
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

        <!-- Test Role Selection (Optional) -->
        <div>
            <label for="test_role" class="block text-sm font-medium text-gray-700 mb-2">
                 Test Role Override (Optional)
            </label>
            <select 
                id="test_role" 
                name="test_role" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition bg-white"
            >
                <option value="" selected>-- Use registered role --</option>
                <option value="admin"> Admin</option>
                <option value="operator"> Operator</option>
                <option value="viewer"> Viewer</option>
            </select>
            <p class="mt-1 text-xs text-gray-500">For testing: temporarily override your role after login. Leave empty to use your actual registered role.</p>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer" 
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-purple-600 hover:text-purple-700 font-medium transition" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-lg hover:shadow-xl">
             Sign In
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or</span>
        </div>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-700 font-semibold transition">
                Sign up here
            </a>
        </p>
    </div>

    <!-- Info Box -->
    <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-xs text-blue-700">
            <span class="font-semibold"> Tip:</span> Use your government-issued email address for login.
        </p>
    </div>
</x-guest-layout>
