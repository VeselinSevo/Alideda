<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Stores</h2>
        <p class="text-sm text-muted">Admin management</p>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('admin.dashboard') }}" class="text-secondary underline">← Back to Admin</a>
            </div>

            <form method="GET" class="mb-6 bg-surface border border-border rounded-xl shadow p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <div>
                        <label class="block text-sm text-muted mb-1">Search</label>
                        <input name="q" value="{{ request('q') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="store name...">
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Country</label>
                        <select name="country"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="">All</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" @selected((string) request('country') === (string) $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Verified</label>
                        <select name="verified"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="">All</option>
                            <option value="1" @selected(request('verified') === '1')>Verified</option>
                            <option value="0" @selected(request('verified') === '0')>Not verified</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Sort</label>
                        <select name="sort"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Latest</option>
                            <option value="name_asc" @selected(request('sort') === 'name_asc')>Name: A → Z</option>
                            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name: Z → A</option>
                            <option value="products_desc" @selected(request('sort') === 'products_desc')>Most products
                            </option>
                            <option value="country_asc" @selected(request('sort') === 'country_asc')>Country: A → Z
                            </option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 flex gap-2">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">Apply</button>
                    <a href="{{ route('admin.stores.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </div>
            </form>

            <div class="bg-surface shadow sm:rounded-lg p-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-left border-b bg-background">
                            <th class="py-2 px-3">Store</th>
                            <th class="py-2 px-3">Owner</th>
                            <th class="py-2 px-3">Products</th>
                            <th class="py-2 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                            <tr class="border-b">
                                <td class="py-2 px-3">
                                    <div class="flex items-center gap-2">
                                        <a class="text-secondary hover:underline" href="{{ route('stores.show', $store) }}">
                                            {{ $store->name }}
                                        </a>

                                        {{-- Verified badge --}}
                                        @if($store->verified)
                                            <span class="inline-flex items-center gap-1 text-xs text-success font-medium">
                                                ✓ Verified
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-2 px-3">
                                    {{ optional($store->owner)->email ?? '—' }}
                                </td>

                                <td class="py-2 px-3">
                                    {{ $store->products_count }}
                                </td>

                                <td class="py-2 px-3">
                                    <div class="flex justify-end gap-2">

                                        {{-- Verify / Unverify --}}
                                        <form method="POST" action="{{ route('admin.stores.verify', $store) }}">
                                            @csrf
                                            @method('PATCH')

                                            @if($store->verified)
                                                <button class="bg-warning text-white px-3 py-1 rounded hover:opacity-90">
                                                    Unverify
                                                </button>
                                            @else
                                                <button class="bg-success text-white px-3 py-1 rounded hover:opacity-90">
                                                    Verify
                                                </button>
                                            @endif
                                        </form>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.stores.destroy', $store) }}"
                                            onsubmit="return confirm('Delete this store?')">
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

                @if($stores->isEmpty())
                    <p class="text-muted p-3">No stores found.</p>
                @endif
            </div>
            <div class="mt-4">
                {{ $stores->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
