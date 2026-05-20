<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card { transition: transform 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-xl font-bold text-purple-600">🛡️ GovLog Sentinel</span>
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium text-purple-600 border-b-2 border-purple-600">Dashboard</a>
                        <a href="{{ route('admin.logs.index') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">Logs</a>
                        <a href="{{ route('admin.logs.critical') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">Critical Alerts</a>
                        <a href="{{ route('admin.reports.audit') }}" class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">Reports</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.users.create-admin') }}" class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">Create Admin</a>
                        @endif
                    </div>
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Logs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalLogs) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Critical Logs</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($criticalLogs) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Warnings</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($warningLogs) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 stat-card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.66 0 3-4 3-9s-1.34-9-3-9m0 18c-1.66 0-3-4-3-9s1.34-9 3-9"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Active IPs (5min)</p>
                        <p class="text-2xl font-bold text-gray-900" id="activeIPs">--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Logs by Classification (7 Days)</h3>
                <canvas id="classificationChart" height="250"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Severity Trend (30 Days)</h3>
                <canvas id="severityChart" height="250"></canvas>
            </div>
        </div>

        <!-- Recent Critical Alerts -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Recent Critical Alerts</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentCritical as $log)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                    {{ strtoupper($log->severity) }}
                                </span>
                                <span class="ml-2 text-sm text-gray-500">{{ $log->classification }}</span>
                            </div>
                            <p class="mt-1 text-gray-900">{{ $log->message }}</p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $log->created_at->diffForHumans() }} • IP: {{ $log->source_ip }} • 
                                {{ $log->user?->email ?? 'System' }}
                            </p>
                        </div>
                        <a href="{{ route('admin.logs.show', $log) }}" class="text-purple-600 hover:text-purple-800">View →</a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    No critical alerts in the last 30 days
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Top Actions (Last 7 Days)</h3>
            </div>
            <div class="px-6 py-4">
                @foreach($topActions as $action)
                <div class="mb-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">{{ $action->action_type }}</span>
                        <span class="text-gray-500">{{ number_format($action->total) }} times</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ min(100, ($action->total / max($topActions->first()->total, 1)) * 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Classification Chart
        const classificationData = @json($logsByClassification);
        const classificationCtx = document.getElementById('classificationChart').getContext('2d');
        new Chart(classificationCtx, {
            type: 'pie',
            data: {
                labels: classificationData.map(d => d.classification),
                datasets: [{
                    data: classificationData.map(d => d.total),
                    backgroundColor: ['#8B5CF6', '#EF4444', '#10B981', '#F59E0B', '#3B82F6', '#EC4899', '#06B6D4']
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });

        // Severity Trend Chart
        const severityData = @json($logsBySeverity);
        const groupedByDate = {};
        severityData.forEach(d => {
            if (!groupedByDate[d.date]) groupedByDate[d.date] = {};
            groupedByDate[d.date][d.severity] = d.total;
        });
        const dates = Object.keys(groupedByDate).sort();
        const datasets = [
            { label: 'info', data: dates.map(d => groupedByDate[d]?.info || 0), borderColor: '#3B82F6', backgroundColor: 'transparent' },
            { label: 'warning', data: dates.map(d => groupedByDate[d]?.warning || 0), borderColor: '#F59E0B', backgroundColor: 'transparent' },
            { label: 'error', data: dates.map(d => groupedByDate[d]?.error || 0), borderColor: '#EF4444', backgroundColor: 'transparent' },
            { label: 'critical', data: dates.map(d => groupedByDate[d]?.critical || 0), borderColor: '#DC2626', backgroundColor: 'transparent' }
        ];
        new Chart(document.getElementById('severityChart').getContext('2d'), {
            type: 'line',
            data: { labels: dates, datasets: datasets },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Real-time stats
        function fetchRealtimeStats() {
            fetch('{{ route("admin.dashboard.realtime") }}')
                .then(res => res.json())
                .then(data => document.getElementById('activeIPs').textContent = data.unique_ips)
                .catch(err => console.error('Stats fetch failed:', err));
        }
        setInterval(fetchRealtimeStats, 30000);
        fetchRealtimeStats();
    </script>
</body>
</html>