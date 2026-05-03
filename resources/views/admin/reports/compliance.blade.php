<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compliance Report - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-purple-600">🛡️ GovLog Sentinel</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('admin.reports.audit') }}" class="text-gray-600 hover:text-gray-900">Audit Report</a>
                    <span class="text-purple-600 border-b-2 border-purple-600 pb-1">Compliance</span>
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
        <h1 class="text-2xl font-bold mb-6">AICTE Compliance Report</h1>

        <!-- Compliance Status -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <div class="flex items-center mb-4">
                <div class="text-3xl mr-3">✅</div>
                <div>
                    <h2 class="text-lg font-semibold text-green-700">System Compliance Status: Active</h2>
                    <p class="text-sm text-gray-500">Last verified: {{ $complianceData['last_audit_date'] }}</p>
                </div>
            </div>
        </div>

        <!-- Compliance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-blue-100 rounded-full mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700">Total Logs Retained</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($complianceData['total_logs_retained']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Retention: {{ $complianceData['audit_retention_days'] }} days</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-green-100 rounded-full mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700">Encryption</h3>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ $complianceData['encryption_status'] }}</p>
                <p class="text-sm text-green-600 mt-1">✓ Active</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-purple-100 rounded-full mr-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700">Access Control</h3>
                </div>
                <p class="text-lg font-bold text-gray-900">{{ $complianceData['access_control'] }}</p>
                <p class="text-sm text-green-600 mt-1">✓ Configured</p>
            </div>
        </div>

        <!-- Integrity Check -->
        <div class="bg-white rounded-lg shadow mb-6 p-6">
            <h2 class="text-lg font-semibold mb-4">Log Integrity Verification</h2>
            @php
                $integrity = $complianceData['integrity_verified'];
                $integrityPct = $integrity['total'] > 0 ? round(($integrity['valid'] / $integrity['total']) * 100, 2) : 100;
            @endphp
            <div class="flex items-center gap-4 mb-3">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $integrityPct == 100 ? 'bg-green-500' : 'bg-red-500' }}"
                             style="width: {{ $integrityPct }}%"></div>
                    </div>
                </div>
                <span class="text-xl font-bold {{ $integrityPct == 100 ? 'text-green-600' : 'text-red-600' }}">{{ $integrityPct }}%</span>
            </div>
            <p class="text-sm text-gray-600">
                {{ $integrity['valid'] }} valid / {{ $integrity['total'] }} total logs •
                {{ $integrity['invalid'] }} chain breaks detected
            </p>
        </div>

        <!-- Backup & Operations -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Operational Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-700">Backup Status</span>
                    <span class="text-sm text-green-600 font-medium">{{ $complianceData['backup_status'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-700">Data Retention Policy</span>
                    <span class="text-sm text-gray-900 font-medium">{{ $complianceData['audit_retention_days'] }} days</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-700">Encryption Standard</span>
                    <span class="text-sm text-gray-900 font-medium">{{ $complianceData['encryption_status'] }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-gray-700">Access Control Model</span>
                    <span class="text-sm text-gray-900 font-medium">{{ $complianceData['access_control'] }}</span>
                </div>
            </div>
        </div>

        <!-- AICTE Compliance Checklist -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">AICTE E-Governance Compliance Checklist</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-green-500 text-lg">✅</span>
                    <span class="text-gray-700">Immutable audit trails maintained for all system events</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-500 text-lg">✅</span>
                    <span class="text-gray-700">Cryptographic hash chaining ensures log integrity</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-500 text-lg">✅</span>
                    <span class="text-gray-700">Real-time monitoring and multi-channel alerting enabled</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-500 text-lg">✅</span>
                    <span class="text-gray-700">Role-based access control implemented</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-500 text-lg">✅</span>
                    <span class="text-gray-700">Data retention policy configured ({{ $complianceData['audit_retention_days'] }} days)</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-green-500 text-lg">✅</span>
                    <span class="text-gray-700">Sensitive data encrypted at rest ({{ $complianceData['encryption_status'] }})</span>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500">Report generated: {{ $complianceData['last_audit_date'] }}</p>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="{{ route('admin.reports.audit') }}" class="text-purple-600 hover:text-purple-800">← Audit Report</a>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
