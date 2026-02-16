<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Store</h2>
                <p class="text-sm text-muted">Details & products</p>
            </div>

            <a href="{{ route('stores.index') }}" class="text-secondary hover:underline">
                ← Back to All Stores
            </a>
        </div>
    </x-slot>

    @php
        $isOwner = auth()->check() && auth()->user()->stores()->whereKey($store->id)->exists();
        $countryCode = strtolower($store->country?->code ?? '');
        $flagUrl = $countryCode ? "https://flagcdn.com/24x18/{$countryCode}.png" : null;
    @endphp

    <div class="py-8 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded p-3 text-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- STORE HEADER CARD --}}
            <div class="bg-surface border border-border rounded-xl shadow p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                    <div class="min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-3xl font-bold text-text">
                                {{ $store->name }}
                            </h1>

                            @if($store->verified)
                                <span
                                    class="inline-flex items-center gap-2 text-sm font-medium text-success bg-background border border-border px-3 py-1 rounded-full">
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-success text-white">✓</span>
                                    Verified
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center text-sm text-muted bg-background border border-border px-3 py-1 rounded-full">
                                    Not verified
                                </span>
                            @endif
                        </div>

                        {{-- INFO CHIPS --}}
                        <div class="mt-4 flex flex-wrap gap-2">
                            {{-- Country --}}
                            <span
                                class="inline-flex items-center gap-2 text-sm bg-background border border-border px-3 py-2 rounded-full text-text">
                                @if($flagUrl)
                                    <img src="{{ $flagUrl }}" width="24" height="18" class="rounded-sm" alt="flag">
                                @endif
                                <span class="text-muted">Country:</span>
                                <span class="font-medium">{{ $store->country?->name ?? '—' }}</span>
                            </span>

                            {{-- Location --}}
                            <span
                                class="inline-flex items-center gap-2 text-sm bg-background border border-border px-3 py-2 rounded-full text-text">
                                <span class="text-muted">Location:</span>
                                <span class="font-medium">
                                    {{ $store->address ?? '—' }}{{ $store->city ? ', ' . $store->city : '' }}
                                </span>
                            </span>

                            {{-- Phone --}}
                            <span
                                class="inline-flex items-center gap-2 text-sm bg-background border border-border px-3 py-2 rounded-full text-text">
                                <span class="text-muted">Phone:</span>
                                <span class="font-medium">{{ $store->phone ?? '—' }}</span>
                            </span>

                            {{-- Products count --}}
                            <span
                                class="inline-flex items-center gap-2 text-sm bg-background border border-border px-3 py-2 rounded-full text-text">
                                <span class="text-muted">Products:</span>
                                <span class="font-bold">{{ $store->products->count() }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- OWNER ACTIONS --}}
                    @if($isOwner)
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('stores.edit', $store) }}"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                                Edit store
                            </a>

                            <form action="{{ route('stores.destroy', $store) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this store?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-danger text-white px-4 py-2 rounded-lg hover:opacity-90">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PRODUCTS HEADER --}}
            <div class="mt-8 flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h2 class="text-lg font-semibold text-text">Products</h2>
                    <p class="text-sm text-muted">Browse what this store is selling</p>
                </div>

                @if($isOwner)
                    <a href="{{ route('products.create', ['store_id' => $store->id]) }}"
                        class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-accent-dark">
                        + Create product
                    </a>
                @endif
            </div>

            {{-- PRODUCTS GRID --}}
            @if($store->products->isEmpty())
                <div class="mt-4 bg-surface border border-border rounded-xl shadow p-6">
                    <p class="text-muted">No products in this store yet.</p>
                </div>
            @else
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($store->products as $product)
                        @php
                            // Works best if you eager load primaryImage in controller later:
                            // Store::with(['products.primaryImage'])->...
                            $thumb = $product->primaryImage
                                ? ($product->primaryImage?->path ?? asset('images/no-image.jpg'))
                                : asset('images/no-image.jpg');
                        @endphp

                        <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <div class="aspect-[4/3] bg-background border-b border-border overflow-hidden">
                                    <img src="{{ $thumb }}" class="w-full h-full object-cover" alt="product">
                                </div>
                            </a>

                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <a href="{{ route('products.show', $product) }}"
                                        class="text-text font-semibold text-lg hover:underline">
                                        {{ $product->name }}
                                    </a>

                                    <div class="text-right">
                                        <p class="text-xs text-muted">Price</p>
                                        <p class="text-lg font-bold text-text">
                                            ${{ number_format((float) $product->price, 2) }}
                                        </p>
                                    </div>
                                </div>

                                <p class="text-sm text-muted mt-2 line-clamp-2">
                                    {{ $product->description ?? 'No description.' }}
                                </p>

                                <div class="mt-4 flex items-center justify-between gap-2">
                                    <a href="{{ route('products.show', $product) }}"
                                        class="bg-accent text-white px-3 py-2 rounded-lg hover:bg-accent-dark">
                                        View
                                    </a>

                                    @if($isOwner)
                                        <div class="flex gap-2">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="border border-border text-text px-3 py-2 rounded-lg hover:bg-background">
                                                Edit
                                            </a>

                                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                onsubmit="return confirm('Delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-danger text-white px-3 py-2 rounded-lg hover:opacity-90">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
