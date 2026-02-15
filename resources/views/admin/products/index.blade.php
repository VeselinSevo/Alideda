<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Products</h2>
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
                <div class="grid grid-cols-1 md:grid-cols-6 gap-3">

                    <div class="md:col-span-2">
                        <label class="block text-sm text-muted mb-1">Search</label>
                        <input name="q" value="{{ request('q') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="Product name...">
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Store</label>
                        <select name="store"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="">All</option>
                            @foreach($stores as $s)
                                <option value="{{ $s->id }}" @selected((string) request('store') === (string) $s->id)>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
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
                        <label class="block text-sm text-muted mb-1">Min price</label>
                        <input name="min" value="{{ request('min') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="0">
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Max price</label>
                        <input name="max" value="{{ request('max') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="999">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-muted mb-1">Sort</label>
                        <select name="sort"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Latest</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low → High
                            </option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High → Low
                            </option>
                            <option value="name_asc" @selected(request('sort') === 'name_asc')>Name: A → Z</option>
                            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name: Z → A</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 flex gap-2">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                        Apply
                    </button>
                    <a href="{{ route('products.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </div>
            </form>

            <div class="bg-surface shadow sm:rounded-lg p-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-left border-b bg-background">
                            <th class="py-2 px-3">Product</th>
                            <th class="py-2 px-3">Store</th>
                            <th class="py-2 px-3">Price</th>
                            <th class="py-2 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr class="border-b">
                                <td class="py-2 px-3">
                                    <a class="text-secondary underline" href="{{ route('products.show', $product) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="py-2 px-3">
                                    @if($product->store)
                                        <a class="text-secondary underline" href="{{ route('stores.show', $product->store) }}">
                                            {{ $product->store->name }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-2 px-3">${{ $product->price }}</td>
                                <td class="py-2 px-3 text-right">
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                        onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-danger text-white px-3 py-1 rounded hover:opacity-90">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($products->isEmpty())
                    <p class="text-muted p-3">No products found.</p>
                @endif
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
