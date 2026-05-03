<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Details - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-purple-600">🛡️ GovLog Sentinel</a>
                    <a href="{{ route('admin.logs.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Logs</a>
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

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">Log Details</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $log->severity == 'critical' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $log->severity == 'error' ? 'bg-orange-100 text-orange-800' : '' }}
                    {{ $log->severity == 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $log->severity == 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                    {{ strtoupper($log->severity) }}
                </span>
                <span class="ml-2 text-sm text-gray-500">Log ID: {{ $log->log_entry_id }}</span>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Timestamp</label>
                    <p class="mt-1 text-gray-900">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Message</label>
                    <p class="mt-1 text-gray-900">{{ $log->message }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Action Type</label>
                        <p class="mt-1 text-gray-900">{{ $log->action_type }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Classification</label>
                        <p class="mt-1 text-gray-900">{{ $log->classification }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Source IP</label>
                        <p class="mt-1 text-gray-900">{{ $log->source_ip }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">User</label>
                        <p class="mt-1 text-gray-900">{{ $log->user?->email ?? 'System' }}</p>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Request ID</label>
                    <p class="mt-1 text-gray-900 font-mono text-sm">{{ $log->request_id }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Hash (Integrity)</label>
                    <p class="mt-1 text-gray-900 font-mono text-xs break-all">{{ $log->hash }}</p>
                </div>

                @if($log->context)
                <div>
                    <label class="text-sm font-medium text-gray-500">Context Data</label>
                    <pre class="mt-2 p-3 bg-gray-100 rounded-lg text-xs overflow-auto">{{ json_encode($log->context, JSON_PRETTY_PRINT) }}</pre>
                </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between">
                <div class="space-x-2">
                    @if($previousLog)
                    <a href="{{ route('admin.logs.show', $previousLog) }}" class="text-purple-600 hover:text-purple-800">← Previous</a>
                    @endif
                    @if($nextLog)
                    <a href="{{ route('admin.logs.show', $nextLog) }}" class="text-purple-600 hover:text-purple-800">Next →</a>
                    @endif
                </div>
                <a href="{{ route('admin.logs.index') }}" class="text-gray-600 hover:text-gray-900">Back to Logs</a>
            </div>
        </div>
    </div>
</body>
</html>