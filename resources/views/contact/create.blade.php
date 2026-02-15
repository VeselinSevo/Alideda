<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Contact Admin</h2>
                <p class="text-sm text-muted">Send a message to the administrators</p>
            </div>
            <a href="{{ route('stores.index') }}" class="text-secondary hover:underline">
                ← Back to Stores
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded p-4 text-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-surface border border-danger rounded p-4 text-danger">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-surface border border-border rounded-xl shadow p-6">
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf

                    @guest
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm text-muted mb-1">Your name</label>
                                <input name="name" value="{{ old('name') }}"
                                    class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-sm text-muted mb-1">Your email</label>
                                <input name="email" type="email" value="{{ old('email') }}"
                                    class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            </div>
                        </div>
                    @endguest

                    <div class="mb-4">
                        <label class="block text-sm text-muted mb-1">Subject</label>
                        <input name="subject" value="{{ old('subject') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="e.g. Problem with my order">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-muted mb-1">Message</label>
                        <textarea name="message" rows="7"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="Write your message...">{{ old('message') }}</textarea>
                    </div>

                    <button class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-primary-dark">
                        Send Message
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
