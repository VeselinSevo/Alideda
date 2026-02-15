<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Message #{{ $message->id }}</h2>
                <p class="text-sm text-muted">{{ $message->email ?? $message->user?->email ?? '—' }}</p>
            </div>

            <a href="{{ route('admin.messages.index') }}" class="text-secondary underline">← Back</a>
        </div>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-surface border border-border rounded-xl shadow p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-muted text-sm">From</p>
                        <p class="text-text font-semibold">{{ $message->name ?? $message->user?->name ?? 'Unknown' }}</p>
                        <p class="text-muted">{{ $message->email ?? $message->user?->email ?? '—' }}</p>
                    </div>

                    <div class="text-right">
                        <p class="text-muted text-sm">Received</p>
                        <p class="text-text">{{ $message->created_at?->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-muted text-sm">Subject</p>
                    <p class="text-text font-medium">{{ $message->subject ?: '—' }}</p>
                </div>

                <div class="mt-6">
                    <p class="text-muted text-sm mb-2">Message</p>
                    <div class="bg-background border border-border rounded-lg p-4 text-text whitespace-pre-wrap">
                        {{ $message->message }}
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                        onsubmit="return confirm('Delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-danger text-white px-4 py-2 rounded hover:opacity-90">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
