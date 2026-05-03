<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Critical Alerts - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-purple-600">🛡️ GovLog Sentinel</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('admin.logs.index') }}" class="text-gray-600 hover:text-gray-900">Logs</a>
                    <a href="{{ route('admin.logs.critical') }}" class="text-purple-600 border-b-2 border-purple-600 pb-1">Critical Alerts</a>
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-red-600">Critical Alerts</h1>
            <div class="text-sm text-gray-500">{{ $logs->total() }} total critical alerts</div>
        </div>

        <!-- Critical Alerts List -->
        <div class="space-y-4">
            @forelse($logs as $log)
            <div class="bg-white rounded-lg shadow border-l-4 border-red-500 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">CRITICAL</span>
                                <span class="text-sm text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                                <span class="text-sm text-gray-500">IP: {{ $log->source_ip }}</span>
                            </div>
                            <p class="text-gray-900 mb-2">{{ $log->message }}</p>
                            <div class="flex items-center space-x-4 text-sm">
                                <span class="text-gray-500">Classification: {{ $log->classification }}</span>
                                <span class="text-gray-500">Action: {{ $log->action_type }}</span>
                                <span class="text-gray-500">User: {{ $log->user?->email ?? 'System' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.logs.show', $log) }}" class="text-purple-600 hover:text-purple-800 font-medium">View Details →</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                No critical alerts found
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
</body>
</html>