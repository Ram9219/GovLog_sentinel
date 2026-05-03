<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-gradient { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-purple-600">🛡️ GovLog Sentinel</span>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Sign up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
        <section class="hero-gradient text-white rounded-lg p-12 text-center">
            <h1 class="text-4xl font-extrabold">Secure. Monitor. Alert.</h1>
            <p class="mt-4 max-w-2xl mx-auto">GovLog Sentinel delivers immutable government-grade logging, classification, and multi-channel alerting for critical systems.</p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="#" class="bg-white text-purple-700 px-6 py-3 rounded-lg font-semibold">Get Started</a>
                <a href="#features" class="border border-white text-white px-6 py-3 rounded-lg">Learn more</a>
            </div>
        </section>

        <section id="features" class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold">Immutable Chain</h3>
                <p class="mt-2 text-sm text-gray-600">SHA-256 chained logs for audit-grade integrity.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold">Multi-channel Alerts</h3>
                <p class="mt-2 text-sm text-gray-600">SMS, WhatsApp, Email, and real-time broadcasts.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold">Rule-based Classification</h3>
                <p class="mt-2 text-sm text-gray-600">Custom classification rules managed in the admin UI.</p>
            </div>
        </section>

        <footer class="mt-12 text-center text-sm text-gray-500">
            <p>&copy; 2026 GovLog Sentinel. All rights reserved.</p>
        </footer>
    </main>
</body>
</html>
