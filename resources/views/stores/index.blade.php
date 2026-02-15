<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Stores</h2>
                <p class="text-sm text-muted">All Stores</p>
            </div>

            @auth
                <a href="{{ route('stores.create') }}" class="bg-accent text-white px-4 py-2 rounded hover:bg-accent-dark">
                    + Create Store
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-8 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded p-3 text-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="GET" class="mb-6 bg-surface border border-border rounded-xl shadow p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <div>
                        <label class="block text-sm text-muted mb-1">Search</label>
                        <input name="q" value="{{ request('q') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="Store name...">
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
                            <option value="country_asc" @selected(request('sort') === 'country_asc')>Country: A → Z
                            </option>
                            <option value="products_desc" @selected(request('sort') === 'products_desc')>Most products
                            </option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 flex gap-2">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                        Apply
                    </button>
                    <a href="{{ route('stores.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </div>
            </form>

            @if($stores->isEmpty())
                <div class="bg-surface border border-border rounded-xl shadow p-6">
                    <p class="text-muted">No stores yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stores as $store)
                        <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <a href="{{ route('stores.show', $store) }}"
                                            class="text-text font-semibold text-lg hover:underline block truncate">
                                            {{ $store->name }}
                                        </a>

                                        <p class="text-sm text-muted mt-1">
                                            <span class="font-medium text-text">Country:</span>
                                            {{ $store->country?->name ?? '—' }}
                                        </p>

                                        <p class="text-sm text-muted">
                                            {{ $store->address ?? '—' }}
                                            @if($store->city)
                                                , {{ $store->city }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-xs text-muted">Products</p>
                                        <p class="text-2xl font-bold text-text">
                                            {{ $store->products_count ?? $store->products->count() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <a href="{{ route('stores.show', $store) }}"
                                        class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                                        View store
                                    </a>

                                    <span class="text-xs text-muted">
                                        {{ $store->phone ? '☎ ' . $store->phone : '' }}
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-3 bg-background border-t border-border">
                                <span class="text-xs text-muted">
                                    {{-- Verified badge --}}
                                    @if($store->verified)
                                        <span class="inline-flex items-center gap-1 text-xs text-success font-medium">
                                            ✓ Verified
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="mt-8">
                    {{ $stores->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>