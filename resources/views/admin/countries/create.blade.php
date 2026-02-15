<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="font-semibold text-xl text-text leading-tight">Create Country</h2>
            <a href="{{ route('admin.countries.index') }}" class="text-secondary underline">← Back to Countries</a>
        </div>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 text-danger">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-surface border border-border shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.countries.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-text">Name</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="name" value="{{ old('name') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-text">Code (2 letters)</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="code" value="{{ old('code') }}"
                            maxlength="2">
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('admin.countries.index') }}"
                            class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                            Cancel
                        </a>

                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                            Create
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
