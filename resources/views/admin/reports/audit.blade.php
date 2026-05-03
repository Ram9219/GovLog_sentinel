<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Report - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-purple-600">🛡️ GovLog Sentinel</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('admin.reports.audit') }}" class="text-purple-600 border-b-2 border-purple-600 pb-1">Audit Report</a>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">AICTE Compliance Audit Report</h1>

        <!-- Integrity Section -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-semibold mb-4">Log Integrity Status</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $integrityResult['valid'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Valid Logs</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $integrityResult['invalid'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Invalid Logs</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format(($integrityResult['total'] ?? 0) > 0 ? (($integrityResult['valid'] ?? 0) / $integrityResult['total']) * 100 : 100, 2) }}%</div>
                    <div class="text-sm text-gray-600">Integrity Score</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $integrityResult['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Total Logs Checked</div>
                </div>
            </div>
        </div>

        <!-- Log Statistics -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-semibold mb-4">Log Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">By Classification</h3>
                    @foreach($stats['by_classification'] ?? [] as $stat)
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ $stat->classification }}</span>
                        <span>{{ number_format($stat->total) }}</span>
                    </div>
                    @endforeach
                </div>
                <div>
                    <h3 class="font-medium text-gray-700 mb-2">By Severity</h3>
                    @foreach($stats['by_severity'] ?? [] as $stat)
                    <div class="flex justify-between text-sm mb-1">
                        <span>{{ ucfirst($stat->severity) }}</span>
                        <span>{{ number_format($stat->total) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Compliance Statement -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">AICTE Compliance Statement</h2>
            <div class="prose text-gray-600">
                <p class="mb-2">✓ Audit trails maintained for all system events</p>
                <p class="mb-2">✓ Cryptographic hash chaining ensures log immutability</p>
                <p class="mb-2">✓ Real-time monitoring and alerting for security incidents</p>
                <p class="mb-2">✓ Role-based access control implemented</p>
                <p class="mb-2">✓ Data retention policy: {{ config('app.log_retention_days', 365) }} days</p>
                <p class="mt-4 text-sm text-gray-500">Last verified: {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>
</body>
</html>