<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-purple-600 dark:text-purple-300 font-semibold">GovLog Sentinel</p>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>

            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-lg border border-purple-200 bg-white px-4 py-2 text-purple-700 shadow-sm transition hover:bg-purple-50">
                    🏠 Landing Page
                </a>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-white shadow-sm transition hover:bg-purple-700">
                    ⚙️ Profile
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-gradient-to-br from-purple-700 via-purple-600 to-indigo-700 text-white shadow-2xl ring-1 ring-purple-200/40">
                <div class="grid gap-8 p-8 lg:grid-cols-[1.4fr_0.9fr] lg:p-10">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span>🛡️</span>
                            <span>Secure monitoring is active</span>
                        </div>

                        <div class="space-y-3">
                            <h3 class="text-3xl font-bold tracking-tight sm:text-4xl">
                                Welcome back, {{ Auth::user()->name }}
                            </h3>
                            <p class="max-w-2xl text-sm leading-6 text-purple-100 sm:text-base">
                                GovLog Sentinel is ready for log review, compliance tracking, and alert monitoring. Your profile details are now linked to the dashboard for quicker access.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.logs.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-purple-700 shadow-lg transition hover:-translate-y-0.5 hover:bg-purple-50">
                                📋 Review Logs
                            </a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                                👤 Edit Profile
                            </a>
                            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                🏠 Open Landing Page
                            </a>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-sm ring-1 ring-white/15">
                        <div class="mb-4 flex items-center justify-between">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-purple-100">Profile Snapshot</h4>
                            <span class="rounded-full bg-emerald-400/20 px-3 py-1 text-xs font-semibold text-emerald-100">Active</span>
                        </div>

                        <div class="space-y-4 text-sm">
                            <div class="rounded-xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-wider text-purple-200">Name</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="rounded-xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-wider text-purple-200">Email</p>
                                <p class="mt-1 text-base font-semibold text-white break-all">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="rounded-xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-wider text-purple-200">Phone</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ Auth::user()->phone_number ?? 'Not provided yet' }}</p>
                            </div>
                            <div class="rounded-xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-wider text-purple-200">Role</p>
                                <p class="mt-1 text-base font-semibold text-white">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Logged in as</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->email }}</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Contact Number</p>
                    <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ Auth::user()->phone_number ?? 'Add from your profile' }}</p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Used for SMS alerts and account contact.</p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Quick Access</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('profile.edit') }}" class="rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Profile</a>
                        <a href="{{ route('landing') }}" class="rounded-lg border border-purple-200 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-50">Landing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
