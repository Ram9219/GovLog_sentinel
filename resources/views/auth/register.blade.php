<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h2>
        <p class="text-gray-600 text-sm">Join the GovLog Sentinel system for secure log management</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                👤 Full Name
            </label>
            <x-text-input 
                id="name" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="Your Full Name" 
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
        </div>

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
                autocomplete="username"
                placeholder="your.email@government.in" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Phone Number -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                📱 Phone Number
            </label>
            <x-text-input 
                id="phone" 
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                type="tel" 
                name="phone" 
                :value="old('phone')" 
                required 
                autocomplete="tel"
                placeholder="+91XXXXXXXXXX" 
            />
            <p class="mt-1 text-xs text-gray-500">Use the number where SMS alerts should be delivered.</p>
            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-600 text-sm" />
        </div>

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
                autocomplete="new-password"
                placeholder="••••••••" 
            />
            <p class="mt-1 text-xs text-gray-500">At least 8 characters with uppercase, lowercase, and numbers</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                🔐 Confirm Password
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

        <!-- Terms Agreement -->
        <div class="flex items-start">
            <input 
                id="terms" 
                type="checkbox" 
                class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500 cursor-pointer mt-0.5" 
                name="terms"
                required
            >
            <label for="terms" class="ml-2 text-sm text-gray-600">
                I agree to the <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">Terms & Conditions</a> and <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">Privacy Policy</a>
            </label>
        </div>

        <!-- Register Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 transition shadow-lg hover:shadow-xl mt-6">
            ✨ Create Account
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

    <!-- Login Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold transition">
                Sign in here
            </a>
        </p>
    </div>

    <!-- Features Box -->
    <div class="mt-6 space-y-2 p-4 bg-purple-50 border border-purple-200 rounded-lg">
        <p class="text-xs font-semibold text-purple-900 mb-3">✨ System Features:</p>
        <div class="space-y-1 text-xs text-purple-800">
            <p>🔒 Secure encryption for all logs</p>
            <p>📊 Real-time analytics dashboard</p>
            <p>🔔 Multi-channel alerts (SMS, Email, WhatsApp)</p>
            <p>📋 Comprehensive audit trails</p>
        </div>
    </div>
</x-guest-layout>
