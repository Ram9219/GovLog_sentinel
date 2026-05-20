<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-purple-600 font-semibold">Viewer Area</p>
                <h2 class="font-semibold text-2xl text-slate-900 leading-tight">
                    {{ __('Accessible Logs') }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">Non-sensitive logs only. Critical and emergency entries stay in the admin panel.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="inline-flex w-full items-center justify-center rounded-lg border border-purple-200 bg-white px-4 py-2 text-sm font-semibold text-purple-700 shadow-sm hover:bg-purple-50 sm:w-auto">← Dashboard</a>
                <a href="{{ route('landing') }}" class="inline-flex w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-700 sm:w-auto">Landing Page</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-white/90 p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-slate-500">Visible Logs</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($logs->total()) }}</p>
                </div>
                <div class="rounded-2xl bg-white/90 p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-slate-500">Severity Levels</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">3</p>
                </div>
                <div class="rounded-2xl bg-white/90 p-6 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-semibold text-slate-500">Access Mode</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">Read Only</p>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <form method="GET" class="grid gap-3 md:grid-cols-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." class="w-full rounded-lg border-slate-300 px-3 py-2 focus:border-purple-500 focus:ring-purple-500">
                    <select name="severity" class="w-full rounded-lg border-slate-300 px-3 py-2 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">All Severities</option>
                        @foreach($severities as $severity)
                            <option value="{{ $severity }}" @selected(request('severity') === $severity)>{{ ucfirst($severity) }}</option>
                        @endforeach
                    </select>
                    <select name="classification" class="w-full rounded-lg border-slate-300 px-3 py-2 focus:border-purple-500 focus:ring-purple-500">
                        <option value="">All Classifications</option>
                        @foreach($classifications as $classification)
                            <option value="{{ $classification }}" @selected(request('classification') === $classification)>{{ ucfirst($classification) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-lg bg-purple-600 px-4 py-2 font-semibold text-white hover:bg-purple-700">Filter</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Time</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Severity</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Classification</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Message</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">User</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-600">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $log->severity === 'error' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ strtoupper($log->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-700">{{ $log->classification }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-900">{{ $log->message }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-600">{{ $log->user?->email ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No viewer-accessible logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-4 py-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
