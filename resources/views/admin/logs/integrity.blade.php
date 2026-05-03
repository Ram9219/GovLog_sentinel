<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Integrity Verification - GovLog Sentinel</title>
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
                    <span class="text-purple-600 border-b-2 border-purple-600 pb-1">Integrity Check</span>
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
        <h1 class="text-2xl font-bold mb-6">Log Integrity Verification</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-blue-600">{{ number_format($result['total']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Total Logs Checked</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-green-600">{{ number_format($result['valid']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Valid (Chain Intact)</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold {{ $result['invalid'] > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($result['invalid']) }}</div>
                <div class="text-sm text-gray-600 mt-1">Invalid (Chain Broken)</div>
            </div>
        </div>

        <!-- Integrity Score -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Integrity Score</h2>
            @php
                $percentage = $result['total'] > 0 ? round(($result['valid'] / $result['total']) * 100, 2) : 100;
            @endphp
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="h-4 rounded-full {{ $percentage == 100 ? 'bg-green-500' : ($percentage > 90 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                <span class="text-2xl font-bold {{ $percentage == 100 ? 'text-green-600' : 'text-red-600' }}">{{ $percentage }}%</span>
            </div>
            <p class="mt-2 text-sm text-gray-500">
                @if($percentage == 100)
                    ✅ All log entries have valid hash chains. No tampering detected.
                @else
                    ⚠️ Some log entries have broken hash chains. Review the details below.
                @endif
            </p>
        </div>

        <!-- Details (show only invalid entries if any) -->
        @if($result['invalid'] > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h3 class="text-lg font-semibold text-red-800">Invalid Chain Entries</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($result['details'] as $detail)
                    @if(!$detail['valid'])
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">INVALID</span>
                                <span class="ml-2 text-sm text-gray-600">Log ID: {{ $detail['log_id'] }}</span>
                            </div>
                            <span class="text-sm text-gray-500">{{ $detail['reason'] }}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-green-50 rounded-lg shadow p-8 text-center">
            <div class="text-4xl mb-3">✅</div>
            <h3 class="text-lg font-semibold text-green-800">All Clear</h3>
            <p class="text-green-600 mt-1">All log entries passed integrity verification.</p>
        </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.logs.index') }}" class="text-purple-600 hover:text-purple-800">← Back to Logs</a>
        </div>
    </div>
</body>
</html>
