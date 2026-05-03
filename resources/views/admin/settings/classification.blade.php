<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Classification Rules - GovLog Sentinel</title>
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
                    <span class="text-purple-600 border-b-2 border-purple-600 pb-1">Classification Rules</span>
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
            <h1 class="text-2xl font-bold">Classification Rules</h1>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Add New Rule Form -->
        <div class="bg-white rounded-lg shadow mb-8 p-6">
            <h2 class="text-lg font-semibold mb-4">Add New Classification Rule</h2>
            <form method="POST" action="{{ route('admin.classification.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rule Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="e.g., Brute Force Detection">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Classification</label>
                        <input type="text" name="classification" value="{{ old('classification') }}" required
                               class="w-full border rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="e.g., security_breach">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                        <select name="severity" required class="w-full border rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="info" {{ old('severity') == 'info' ? 'selected' : '' }}>Info</option>
                            <option value="warning" {{ old('severity') == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="error" {{ old('severity') == 'error' ? 'selected' : '' }}>Error</option>
                            <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patterns (comma-separated)</label>
                        <input type="text" name="patterns[]" value="{{ old('patterns.0') }}" required
                               class="w-full border rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="e.g., brute force, login failed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority (0-100)</label>
                        <input type="number" name="priority" value="{{ old('priority', 0) }}" min="0" max="100"
                               class="w-full border rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" checked class="rounded text-purple-600">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        Add Rule
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Rules Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Existing Rules</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classification</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Severity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rules as $rule)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $rule->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $rule->classification }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $rule->severity == 'critical' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $rule->severity == 'error' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $rule->severity == 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $rule->severity == 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ strtoupper($rule->severity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $rule->priority }}</td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.classification.toggle', $rule) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $rule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <form method="POST" action="{{ route('admin.classification.destroy', $rule) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this rule?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No classification rules defined yet. Add one above.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
