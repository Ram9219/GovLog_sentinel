<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logs - GovLog Sentinel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-purple-600">🛡️ GovLog Sentinel</a>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('admin.logs.index') }}" class="text-purple-600 border-b-2 border-purple-600 pb-1">Logs</a>
                    <a href="{{ route('admin.logs.critical') }}" class="text-gray-600 hover:text-gray-900">Critical Alerts</a>
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
        <h1 class="text-2xl font-bold mb-6">System Logs</h1>

        <!-- Search/Filter Form -->
        <div class="bg-white rounded-lg shadow mb-6 p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="text" name="search" placeholder="Search logs..." value="{{ request('search') }}" class="border rounded-lg px-3 py-2">
                <select name="severity" class="border rounded-lg px-3 py-2">
                    <option value="">All Severities</option>
                    @foreach($severities as $sev)
                    <option value="{{ $sev }}" {{ request('severity') == $sev ? 'selected' : '' }}>{{ ucfirst($sev) }}</option>
                    @endforeach
                </select>
                <select name="classification" class="border rounded-lg px-3 py-2">
                    <option value="">All Classifications</option>
                    @foreach($classifications as $class)
                    <option value="{{ $class }}" {{ request('classification') == $class ? 'selected' : '' }}>{{ ucfirst($class) }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From" class="border rounded-lg px-3 py-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To" class="border rounded-lg px-3 py-2">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('admin.logs.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-center hover:bg-green-700">Export CSV</a>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Severity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classification</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $log->severity == 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $log->severity == 'error' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $log->severity == 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $log->severity == 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ strtoupper($log->severity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $log->classification }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-md truncate">{{ $log->message }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->source_ip }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->user?->email ?? 'System' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.logs.show', $log) }}" class="text-purple-600 hover:text-purple-800">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">No logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</body>
</html>