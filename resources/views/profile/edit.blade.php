<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Profile') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- User Info Card -->
            <div class="mb-6 p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-purple-600">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- User Details -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">👤 Personal Information</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-xs text-gray-500">Name</span>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Email</span>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Phone</span>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->phone_number ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Role & Status -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">🔐 Role & Status</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                    Role: {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Status</span>
                                <p class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    <span class="text-sm font-medium text-green-700 dark:text-green-400">Active</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Department & Access -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">🏢 Department & Access</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-xs text-gray-500">Department</span>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ Auth::user()->department ?? 'System-wide Access' }}
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500">Joined</span>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Permissions Info -->
            <div class="mb-6 p-6 bg-blue-50 dark:bg-blue-900/30 shadow sm:rounded-lg border-l-4 border-blue-600">
                <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-3">ℹ️ Your Permissions as {{ ucfirst(Auth::user()->role) }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @if(Auth::user()->role === 'admin')
                        <div>✅ View all logs</div>
                        <div>✅ Manage users & roles</div>
                        <div>✅ Delete logs</div>
                        <div>✅ Configure system settings</div>
                        <div>✅ Access compliance reports</div>
                        <div>✅ Manage classification rules</div>
                    @elseif(Auth::user()->role === 'operator')
                        <div>✅ View department logs</div>
                        <div>✅ Create classification rules</div>
                        <div>✅ Export reports</div>
                        <div>✅ Acknowledge alerts</div>
                        <div>❌ Cannot delete logs</div>
                        <div>❌ Cannot manage other users</div>
                    @elseif(Auth::user()->role === 'viewer')
                        <div>✅ View non-sensitive logs</div>
                        <div>✅ View public reports</div>
                        <div>✅ Access dashboard</div>
                        <div>❌ Cannot modify data</div>
                        <div>❌ Cannot export full logs</div>
                        <div>❌ No rule management</div>
                    @else
                        <div>✅ Create logs (API)</div>
                        <div>✅ Receive notifications</div>
                        <div>❌ Cannot view data</div>
                        <div>❌ Read-only system account</div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 shadow sm:rounded-lg border-l-4 border-green-600">
                <h3 class="text-sm font-semibold text-green-900 dark:text-green-200 mb-3">⚡ Quick Actions</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                        📊 Go to Dashboard
                    </a>
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'operator')
                        <a href="{{ route('admin.logs.index') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            📋 View Logs
                        </a>
                    @endif
                    <a href="{{ route('landing') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                        🏠 Landing Page
                    </a>
                </div>
            </div>

            <!-- Editable Forms -->
            <div class="space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">✏️ Update Profile Information</h3>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">🔒 Update Password</h3>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-red-50 dark:bg-red-900/30 shadow sm:rounded-lg border-l-4 border-red-600">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-semibold mb-4 text-red-900 dark:text-red-200">⚠️ Danger Zone</h3>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
