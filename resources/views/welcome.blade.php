<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>GovLog Sentinel | AICTE Compliant Log Management</title>
    <!-- Simple, human-friendly UI with Tailwind for structure, custom touches for soul -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Adding a subtle interceptor for better spacing and calmness -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, sans-serif;
        }
        body {
            background: #fafbfc;
            scroll-behavior: smooth;
        }
        /* custom minimal shadows & transitions - no heavy gradients, just clean */
        .card-soft {
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
        }
        .card-soft:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.08), 0 4px 8px -4px rgba(0, 0, 0, 0.02);
        }
        .hero-blur-bg {
            background: linear-gradient(115deg, #f3f4ff 0%, #eef2ff 45%, #ffffff 100%);
        }
        .nav-blur {
            backdrop-filter: blur(1px);
            background-color: rgba(255,255,255,0.96);
        }
        .step-number {
            background-color: #4f46e5;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 1rem;
        }
        .badge-light {
            background-color: #e0e7ff;
            color: #2d3a8c;
            border-radius: 100px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        hr.dreamy {
            background: linear-gradient(90deg, transparent, #cbd5e1, transparent);
            height: 1px;
            border: 0;
        }
        .hover-underline {
            transition: border-color 0.2s;
        }
        .btn-outline-simple {
            border: 1px solid #cbd5e1;
            background: white;
            transition: all 0.2s ease;
        }
        .btn-outline-simple:hover {
            border-color: #4f46e5;
            background-color: #f8fafc;
            transform: translateY(-1px);
        }
        .btn-primary-soft {
            background-color: #4f46e5;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s;
        }
        .btn-primary-soft:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(79, 70, 229, 0.2);
        }
        .focus-ring:focus {
            outline: none;
            ring: 2px solid #4f46e5;
            ring-offset: 2px;
        }
        a, button { cursor: pointer; }
        .footer-link {
            transition: color 0.15s;
        }
    </style>
</head>
<body class="antialiased">

    <!-- simple yet sturdy navigation – human crafted -->
    <header class="sticky top-0 z-30 w-full border-b border-gray-100 nav-blur">
        <div class="max-w-6xl mx-auto px-5 sm:px-8">
            <div class="flex items-center justify-between h-16 md:h-18">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🛡️</span>
                    <span class="font-bold text-gray-800 text-xl tracking-tight">GovLog<span class="text-indigo-600">Sentinel</span></span>
                    <span class="hidden md:inline-block ml-2 text-[11px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full uppercase tracking-wide">AICTE Ready</span>
                </div>
                <div class="flex items-center gap-3 sm:gap-5">
                    <!-- using fake auth state - simple representation like a standalone landing
                         but we make it interactive but not overengineered: just nice demo actions -->
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 text-sm font-medium transition">Log in</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-1.5 rounded-full text-sm font-semibold shadow-sm hover:bg-indigo-700 transition-all hover:shadow">Sign up →</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero section – warm, approachable, minimal -->
        <section class="hero-blur-bg px-5 pt-12 pb-20 md:pt-20 md:pb-28">
            <div class="max-w-5xl mx-auto text-center">
                <span class="badge-light inline-block mb-5">🔒 Government-grade compliance</span>
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                    Secure. Monitor. <span class="text-indigo-600">Alert.</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mt-6 max-w-2xl mx-auto leading-relaxed">
                    Immutable logging, auto‑classification & real‑time alerts — built for AICTE and public sector systems.
                </p>
                <div class="flex flex-wrap gap-4 justify-center mt-8">
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold text-base shadow-md hover:bg-indigo-700 transition-all inline-flex items-center gap-2">
                        Get started
                        <span>→</span>
                    </a>
                    <a href="#features" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-medium hover:border-indigo-300 hover:text-indigo-700 transition-all inline-flex items-center gap-1">
                        Explore features
                    </a>
                </div>
                <div class="mt-10 flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm text-gray-500">
                    <span class="flex items-center gap-1">✓ No audit trails tampering</span>
                    <span class="flex items-center gap-1">✓ 24/7 real-time monitoring</span>
                    <span class="flex items-center gap-1">✓ Multi‑channel alerting</span>
                </div>
            </div>
        </section>

        <!-- Features grid – human readable, simple cards -->
        <section id="features" class="py-20 px-5 bg-white">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-14">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">Designed for audit & speed</h2>
                    <div class="w-20 h-1 bg-indigo-500 mx-auto mt-4 rounded-full"></div>
                    <p class="text-gray-500 max-w-xl mx-auto mt-4">Everything you need to maintain compliance and react instantly</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-7">
                    <!-- card 1 -->
                    <div class="card-soft rounded-2xl bg-white border border-gray-100 p-6 transition-all">
                        <div class="text-3xl mb-4">📋</div>
                        <h3 class="text-xl font-semibold text-gray-800">Real-time log capture</h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed">Every system event, timestamp, IP address, and user context captured instantly without gaps.</p>
                    </div>
                    <!-- card 2 -->
                    <div class="card-soft rounded-2xl bg-white border border-gray-100 p-6 transition-all">
                        <div class="text-3xl mb-4">🤖</div>
                        <h3 class="text-xl font-semibold text-gray-800">Auto‑classification</h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed">Intelligent severity & type tagging — from informational to critical, based on dynamic rules.</p>
                    </div>
                    <!-- card 3 -->
                    <div class="card-soft rounded-2xl bg-white border border-gray-100 p-6 transition-all">
                        <div class="text-3xl mb-4">🔗</div>
                        <h3 class="text-xl font-semibold text-gray-800">Blockchain integrity</h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed">Cryptographic hash chaining ensures logs remain tamper-proof and court‑admissible.</p>
                    </div>
                    <!-- card 4 -->
                    <div class="card-soft rounded-2xl bg-white border border-gray-100 p-6 transition-all">
                        <div class="text-3xl mb-4">📱</div>
                        <h3 class="text-xl font-semibold text-gray-800">Multi‑channel alerts</h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed">SMS, WhatsApp, Email, Slack — instant notifications for critical anomalies.</p>
                    </div>
                    <!-- card 5 -->
                    <div class="card-soft rounded-2xl bg-white border border-gray-100 p-6 transition-all">
                        <div class="text-3xl mb-4">📊</div>
                        <h3 class="text-xl font-semibold text-gray-800">Analytics dashboard</h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed">Beautiful graphs, trends, live filters & compliance reports accessible in one click.</p>
                    </div>
                    <!-- card 6 -->
                    <div class="card-soft rounded-2xl bg-white border border-gray-100 p-6 transition-all">
                        <div class="text-3xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-800">Full‑text search</h3>
                        <p class="text-gray-500 mt-2 text-sm leading-relaxed">Blazing fast search across millions of records with filters and export options.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- stats - clean numbers with subtle background -->
        <section class="bg-indigo-50/40 py-16 px-5 border-y border-indigo-100/40">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="space-y-1">
                        <div class="text-4xl md:text-5xl font-black text-indigo-700">∞</div>
                        <p class="text-gray-600 font-medium text-sm">Logs monitored</p>
                    </div>
                    <div class="space-y-1">
                        <div class="text-4xl md:text-5xl font-black text-indigo-700">100%</div>
                        <p class="text-gray-600 font-medium text-sm">Immutable records</p>
                    </div>
                    <div class="space-y-1">
                        <div class="text-4xl md:text-5xl font-black text-indigo-700">&lt; 1s</div>
                        <p class="text-gray-600 font-medium text-sm">Alert delivery</p>
                    </div>
                    <div class="space-y-1">
                        <div class="text-4xl md:text-5xl font-black text-indigo-700">24/7</div>
                        <p class="text-gray-600 font-medium text-sm">Active surveillance</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- how it works – simple steps, no fancy fluff -->
        <section id="how-it-works" class="py-20 px-5 bg-white">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-800">How it works</h2>
                    <p class="text-gray-500 mt-2">Four steps to complete compliance & peace of mind</p>
                </div>
                <div class="space-y-8">
                    <!-- Step 1 -->
                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        <div class="flex-shrink-0">
                            <div class="step-number">1</div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Capture events automatically</h3>
                            <p class="text-gray-500 leading-relaxed mt-1">Agent or API integration captures every user action, system event, and transaction alongside metadata (IP, user-agent, timestamp).</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        <div class="flex-shrink-0">
                            <div class="step-number">2</div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Auto-classify & structure</h3>
                            <p class="text-gray-500 leading-relaxed mt-1">Smart classifiers tag severity (Low, Medium, High, Critical) and type (Security, Access, Data, System). No manual effort required.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        <div class="flex-shrink-0">
                            <div class="step-number">3</div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Generate instant multi-channel alerts</h3>
                            <p class="text-gray-500 leading-relaxed mt-1">Critical anomalies trigger real-time SMS, WhatsApp, email, or webhook notifications to on-call teams.</p>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div class="flex flex-col sm:flex-row gap-5 items-start">
                        <div class="flex-shrink-0">
                            <div class="step-number">4</div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Verify & report with cryptographic proof</h3>
                            <p class="text-gray-500 leading-relaxed mt-1">Run compliance reports (AICTE, ISO), verify hash chain integrity, and export audit-ready evidence in seconds.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- additional human touch: testimonial / trust badge section  (soft) -->
        <section class="bg-gray-50 px-5 py-16 border-t border-gray-100">
            <div class="max-w-4xl mx-auto text-center">
                <div class="flex justify-center gap-1 text-amber-400 text-lg mb-4">★★★★★</div>
                <p class="text-gray-700 italic text-lg max-w-2xl mx-auto">"GovLog Sentinel made our log auditing seamless and AICTE inspection ready within weeks. The immutable chain gives us confidence like never before."</p>
                <p class="mt-4 font-medium text-gray-800">— Directorate of Technical Education</p>
                <p class="text-sm text-gray-400">Compliant since 2025</p>
            </div>
        </section>

        <!-- CTA section – clean, minimal, and human-friendly -->
        <section class="py-20 px-5">
            <div class="max-w-4xl mx-auto rounded-3xl bg-indigo-600 p-8 md:p-12 text-center shadow-xl">
                <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tight">Ready to secure your logs?</h2>
                <p class="text-indigo-100 max-w-xl mx-auto mt-3 text-lg">Join the growing network of institutions achieving total log integrity and real-time alerting.</p>
                <div class="flex flex-wrap gap-4 justify-center mt-8">
                    <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-gray-50 transition-all">Start free trial →</a>
                    <a href="mailto:sales@govlogsentinel.in" class="border border-indigo-300 text-white px-6 py-3 rounded-xl font-medium hover:bg-indigo-500 transition-all">Contact sales</a>
                </div>
                <p class="text-indigo-200 text-sm mt-6">No credit card required • AICTE compliant infrastructure</p>
            </div>
        </section>
    </main>

    <!-- footer: simple, human style  -->
    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8 px-5">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center gap-2 text-white">
                        <span class="text-2xl">🛡️</span>
                        <span class="font-bold text-lg tracking-tight">GovLog<span class="text-indigo-400">Sentinel</span></span>
                    </div>
                    <p class="text-sm text-gray-400 mt-3 leading-relaxed">Enterprise-grade logging for government & education sectors. Immutable, fast, compliant.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white text-sm uppercase tracking-wider mb-3">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('landing') }}#features" class="footer-link hover:text-white transition">Features</a></li>
                        <li><a href="{{ route('register') }}" class="footer-link hover:text-white transition">Pricing</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link hover:text-white transition">AICTE Compliance</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link hover:text-white transition">Security</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white text-sm uppercase tracking-wider mb-3">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('landing') }}#how-it-works" class="footer-link hover:text-white transition">Documentation</a></li>
                        <li><a href="{{ route('landing') }}#features" class="footer-link hover:text-white transition">API Reference</a></li>
                        <li><a href="mailto:support@govlogsentinel.in" class="footer-link hover:text-white transition">Support portal</a></li>
                        <li><a href="{{ route('landing') }}" class="footer-link hover:text-white transition">Status</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white text-sm uppercase tracking-wider mb-3">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('landing') }}" class="footer-link hover:text-white transition">Privacy</a></li>
                        <li><a href="{{ route('landing') }}" class="footer-link hover:text-white transition">Terms of service</a></li>
                        <li><a href="{{ route('landing') }}" class="footer-link hover:text-white transition">GDPR & Data</a></li>
                        <li><a href="{{ route('landing') }}" class="footer-link hover:text-white transition">Cookie policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="dreamy my-6">
            <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-3">
                <p>© 2026 GovLog Sentinel — AICTE Compliant E‑Governance Solution. All rights reserved.</p>
                <div class="flex gap-5">
                    <span>🇮🇳 Made for India’s institutions</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>