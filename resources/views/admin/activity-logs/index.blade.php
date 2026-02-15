<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Activity Logs</h2>
        <p class="text-sm text-muted">Key user activities</p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('admin.dashboard') }}" class="text-secondary underline">← Back to Admin</a>
            </div>

            <div class="bg-surface border border-border rounded-xl shadow p-4 mb-4">
                <form method="GET" class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-sm text-muted mb-1">From</label>
                        <input type="date" name="from" value="{{ $from }}"
                            class="bg-surface text-text border-border rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">To</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="bg-surface text-text border-border rounded-md px-3 py-2">
                    </div>

                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-sm text-muted mb-1">Search</label>
                        <input name="q" value="{{ $q }}" placeholder="action, message, email..."
                            class="w-full bg-surface text-text border-border rounded-md px-3 py-2">
                    </div>

                    <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                        Filter
                    </button>

                    <a href="{{ route('admin.activity-logs.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </form>
            </div>

            <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-background border-b border-border">
                            <tr class="text-left">
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">User</th>
                                <th class="py-3 px-4">Action</th>
                                <th class="py-3 px-4">Message</th>
                                <th class="py-3 px-4">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr class="border-b border-border">
                                    <td class="py-3 px-4 text-muted">
                                        {{ $log->created_at?->format('d M Y H:i') }}
                                    </td>
                                    <td class="py-3 px-4 text-text">
                                        {{ $log->user?->email ?? 'Guest' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded bg-background border border-border text-sm">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-text">
                                        {{ $log->message }}
                                    </td>
                                    <td class="py-3 px-4 text-muted">
                                        {{ $log->ip ?? '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 px-4 text-muted">No activity logs.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
