<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Countries
        </h2>
        <p class="text-sm text-muted">
            Manage countries
        </p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <p class="text-success mb-4">
                    {{ session('success') }}
                </p>
            @endif

            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('admin.dashboard') }}" class="text-secondary hover:underline">
                    ← Back to Admin
                </a>

                <a href="{{ route('admin.countries.create') }}"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                    + Add Country
                </a>
            </div>

            <div class="bg-surface shadow sm:rounded-lg p-4 overflow-x-auto border border-border">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-left border-b border-border bg-background">
                            <th class="py-2 px-3 text-text">Name</th>
                            <th class="py-2 px-3 text-text">Code</th>
                            <th class="py-2 px-3 text-text text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries as $country)
                            <tr class="border-b border-border">
                                <td class="py-2 px-3 text-text">
                                    {{ $country->name }}
                                </td>

                                <td class="py-2 px-3 text-muted font-mono">
                                    {{ $country->code }}
                                </td>

                                <td class="py-2 px-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.countries.edit', $country) }}"
                                            class="bg-secondary text-white px-3 py-1 rounded hover:bg-secondary-dark">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.countries.destroy', $country) }}"
                                            onsubmit="return confirm('Delete this country?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="bg-danger text-white px-3 py-1 rounded hover:opacity-90">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($countries->isEmpty())
                    <p class="text-muted p-3">
                        No countries yet.
                    </p>
                @endif
            </div>
            <div class="mt-6">
                {{ $countries->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
