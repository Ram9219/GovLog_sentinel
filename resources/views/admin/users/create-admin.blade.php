<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Admin - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
                    <span class="text-xl font-bold text-purple-600">Create Admin</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid lg:grid-cols-5 gap-8">
            <section class="lg:col-span-3 bg-white rounded-2xl shadow p-8">
                <h1 class="text-2xl font-bold text-gray-900">Create a new administrator</h1>
                <p class="mt-2 text-sm text-gray-600">Fill in the details below. A random password will be generated and sent by email and SMS.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        Please fix the highlighted fields and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store-admin') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input id="phone_number" name="phone_number" type="tel" value="{{ old('phone_number') }}" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" required>
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <p class="text-xs text-gray-500">Role will be set to Admin automatically.</p>
                        <button type="submit" class="inline-flex items-center rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700">
                            Create Admin
                        </button>
                    </div>
                </form>
            </section>

            <aside class="lg:col-span-2 space-y-4">
                <div class="rounded-2xl bg-indigo-50 border border-indigo-200 p-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-indigo-700">What happens next</h2>
                    <ul class="mt-4 space-y-3 text-sm text-indigo-900">
                        <li>1. The account is created with Admin role.</li>
                        <li>2. A random password is generated.</li>
                        <li>3. The password is sent by email and SMS.</li>
                        <li>4. The user should change it after login.</li>
                    </ul>
                </div>

                <div class="rounded-2xl bg-white border border-gray-200 p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-900">Important</h2>
                    <p class="mt-3 text-sm text-gray-600">Use this only for trusted staff. Admin accounts have full control of the system.</p>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>
