<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">My Stores</h2>
                <p class="text-sm text-muted">Manage your stores and products</p>
            </div>

            <a href="{{ route('stores.create') }}" class="bg-accent text-white px-4 py-2 rounded hover:bg-accent-dark">
                + Create Store
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded p-3 text-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($stores->isEmpty())
                <div class="bg-surface border border-border rounded-xl shadow p-6">
                    <p class="text-muted">You don’t have any stores yet.</p>
                    <a href="{{ route('stores.create') }}"
                        class="inline-block mt-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                        Create your first store
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($stores as $store)
                        <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('stores.show', $store) }}"
                                            class="text-text font-semibold text-lg hover:underline">
                                            {{ $store->name }}
                                        </a>

                                        <p class="text-sm text-muted mt-1">
                                            {{ $store->address ?? '—' }}
                                            @if($store->city)
                                                , {{ $store->city }}
                                            @endif
                                        </p>

                                        <p class="text-sm text-muted">
                                            Phone: {{ $store->phone ?? '—' }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs text-muted">Products</p>
                                        <p class="text-2xl font-bold text-text">
                                            {{ $store->products_count ?? $store->products->count() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('stores.show', $store) }}"
                                        class="bg-primary text-white px-3 py-2 rounded hover:bg-primary-dark">
                                        View
                                    </a>

                                    <a href="{{ route('stores.edit', $store) }}"
                                        class="bg-secondary text-white px-3 py-2 rounded hover:bg-secondary-dark">
                                        Edit
                                    </a>

                                    <a href="{{ route('products.create', ['store_id' => $store->id]) }}"
                                        class="border border-border text-text px-3 py-2 rounded hover:bg-background">
                                        + Product
                                    </a>
                                </div>
                            </div>

                            <div class="px-5 py-3 bg-background border-t border-border">
                                <span class="text-xs text-muted">
                                    Store ID: {{ $store->id }}
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