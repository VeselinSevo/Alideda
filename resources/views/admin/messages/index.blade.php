<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Messages</h2>
        <p class="text-sm text-muted">Contact form inbox</p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

            <div class="flex justify-between items-center mb-4 gap-3 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="text-secondary underline">← Back to Admin</a>

                <form method="GET" class="flex gap-2 items-center flex-wrap">
                    <input name="q" value="{{ $q }}" placeholder="Search name/email/subject"
                        class="bg-surface text-text border-border rounded-md px-3 py-2 w-72">
                    <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Search</button>
                </form>
            </div>

            <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-background border-b border-border">
                        <tr class="text-left">
                            <th class="py-3 px-4">From</th>
                            <th class="py-3 px-4">Subject</th>
                            <th class="py-3 px-4">Received</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $m)
                            <tr class="border-b border-border">
                                <td class="py-3 px-4">
                                    <div class="font-medium text-text">{{ $m->name ?? $m->user?->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-muted">{{ $m->email ?? $m->user?->email ?? '—' }}</div>
                                </td>
                                <td class="py-3 px-4 text-text">
                                    {{ $m->subject ?: '—' }}
                                </td>
                                <td class="py-3 px-4 text-muted">
                                    {{ $m->created_at?->format('d M Y H:i') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($m->read_at)
                                        <span class="text-xs px-2 py-1 rounded border border-border bg-background text-muted">
                                            Read
                                        </span>
                                    @else
                                        <span class="text-xs px-2 py-1 rounded border border-accent bg-accent/10 text-accent">
                                            New
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.messages.show', $m) }}" class="text-secondary hover:underline">
                                        View →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-4 text-muted">No messages.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $messages->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
