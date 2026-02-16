<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Products</h2>
        <p class="text-sm text-muted">All Products</p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

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

            @if($products->isEmpty())
                <div class="bg-surface border border-border rounded-xl shadow p-6">
                    <p class="text-muted">No products yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach($products as $product)
                        @php
                            $primary = $product->primaryImage;
                            $mainUrl = $primary?->path ?? asset('images/no-image.jpg');

                            // if you load images in controller later, you can show more thumbs.
                            // for now we’ll just use primary (and it still works).
                            $thumbs = $product->images ?? collect();
                        @endphp

                        <div class="bg-surface border border-border rounded-xl shadow overflow-hidden flex flex-col h-full">
                            {{-- main image --}}
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <div class="aspect-[4/3] bg-background border-b border-border overflow-hidden">
                                    <img src="{{ $mainUrl }}" alt="Product image"
                                        class="product-card-main w-full h-full object-cover">
                                </div>
                            </a>

                            <div class="p-4 flex flex-col flex-grow">

                                {{-- CONTENT AREA --}}
                                <div class="flex-grow">

                                    {{-- title + price --}}
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <a href="{{ route('products.show', $product) }}"
                                                class="text-text font-semibold text-lg hover:underline">
                                                {{ $product->name }}
                                            </a>

                                            <p class="text-sm text-muted mt-1 min-h-[40px]">
                                                {{ $product->description ?? '' }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-muted">Price</p>
                                            <p class="text-lg font-bold text-text">
                                                ${{ number_format((float) $product->price, 2) }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- store link --}}
                                    <div class="mt-3 text-sm">
                                        @if($product->store)
                                            <span class="text-muted">Store:</span>
                                            <a class="text-secondary hover:underline"
                                                href="{{ route('stores.show', $product->store) }}">
                                                {{ $product->store->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Store: —</span>
                                        @endif
                                    </div>

                                </div>

                                {{-- BUTTON ALWAYS AT BOTTOM --}}
                                <div class="mt-4">
                                    <a href="{{ route('products.show', $product) }}"
                                        class="inline-block w-full text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                                        View Product
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // For each product card: clicking a thumb changes ONLY that card’s main image and borders
            document.querySelectorAll('.bg-surface.border-border.rounded-xl').forEach(card => {
                const main = card.querySelector('.product-card-main');
                const thumbs = card.querySelectorAll('.product-thumb');

                if (!main || thumbs.length === 0) return;

                thumbs.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const newSrc = this.getAttribute('data-image');
                        main.src = newSrc;

                        thumbs.forEach(t => {
                            t.classList.remove('border-accent', 'ring-2', 'ring-accent');
                            t.classList.add('border-border');
                        });

                        this.classList.remove('border-border');
                        this.classList.add('border-accent', 'ring-2', 'ring-accent');
                    });
                });
            });
        });
    </script>
</x-app-layout>
