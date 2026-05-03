<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GovLog Sentinel - AICTE Compliant Log Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .feature-card { transition: transform 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
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

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Secure. Monitor. Alert.</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                GovLog Sentinel delivers immutable government-grade logging, classification, 
                and multi-channel alerting for critical systems.
            </p>
            <div class="flex justify-center gap-4">
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="bg-white text-purple-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-purple-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                        Get Started
                    </a>
                    <a href="#features" class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-700">
                        Learn more
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Key Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md">
                    <div class="text-3xl mb-4">📋</div>
                    <h3 class="text-xl font-semibold mb-2">Real-time Log Capture</h3>
                    <p class="text-gray-600">Monitor and capture all government system events with precise timestamping and IP tracking.</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md">
                    <div class="text-3xl mb-4">🤖</div>
                    <h3 class="text-xl font-semibold mb-2">Auto-Classification</h3>
                    <p class="text-gray-600">Intelligent classification engine categorizes logs by severity and type automatically.</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md">
                    <div class="text-3xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">Blockchain Integrity</h3>
                    <p class="text-gray-600">Cryptographic hash chaining ensures log immutability and tamper-proof auditing.</p>
                </div>
                <!-- Feature 4 -->
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md">
                    <div class="text-3xl mb-4">📱</div>
                    <h3 class="text-xl font-semibold mb-2">Multi-Channel Alerts</h3>
                    <p class="text-gray-600">Critical alerts via SMS, WhatsApp, Email, and real-time notifications.</p>
                </div>
                <!-- Feature 5 -->
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Analytics Dashboard</h3>
                    <p class="text-gray-600">Comprehensive dashboard with charts, trends, and real-time statistics.</p>
                </div>
                <!-- Feature 6 -->
                <div class="feature-card bg-gray-50 p-6 rounded-lg shadow-md">
                    <div class="text-3xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold mb-2">Full-Text Search</h3>
                    <p class="text-gray-600">Lightning-fast full-text search across millions of log entries.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">∞</div>
                    <p class="text-purple-100">Logs Monitored</p>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">100%</div>
                    <p class="text-purple-100">Immutable Records</p>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">&lt; 1s</div>
                    <p class="text-purple-100">Alert Response</p>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <p class="text-purple-100">Monitoring</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">How It Works</h2>
            <div class="max-w-3xl mx-auto">
                <div class="flex gap-4 mb-8">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-purple-600 text-white text-xl font-bold">1</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Capture Events</h3>
                        <p class="text-gray-600">System events are automatically captured with full context and timestamps.</p>
                    </div>
                </div>
                <div class="flex gap-4 mb-8">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-purple-600 text-white text-xl font-bold">2</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Auto-Classify</h3>
                        <p class="text-gray-600">Logs are automatically classified using pattern matching and severity rules.</p>
                    </div>
                </div>
                <div class="flex gap-4 mb-8">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-purple-600 text-white text-xl font-bold">3</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Generate Alerts</h3>
                        <p class="text-gray-600">Critical events trigger immediate multi-channel notifications.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-purple-600 text-white text-xl font-bold">4</div>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Verify & Report</h3>
                        <p class="text-gray-600">View immutable audit trail, run compliance reports, and verify system integrity.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Secure Your Logs?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Join government agencies monitoring critical systems with confidence and compliance.
            </p>
            @auth
                <a href="{{ url('/admin/dashboard') }}" class="bg-white text-purple-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                    Access Dashboard
                </a>
            @else
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('login') }}" class="bg-white text-purple-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                        Sign In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-700">
                            Get Started Free
                        </a>
                    @endif
                </div>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="font-semibold text-white mb-4">🛡️ GovLog Sentinel</h3>
                    <p class="text-sm">Enterprise-grade log monitoring for government systems.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Features</h3>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:text-white">Log Monitoring</a></li>
                        <li><a href="#" class="hover:text-white">Real-time Alerts</a></li>
                        <li><a href="#" class="hover:text-white">Compliance Reports</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-white mb-4">Support</h3>
                    <ul class="text-sm space-y-2">
                        <li><a href="#" class="hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">AICTE Compliance</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; 2026 GovLog Sentinel. All rights reserved. AICTE Compliant E-Governance Solution.</p>
            </div>
        </div>
    </footer>
</body>
</html>