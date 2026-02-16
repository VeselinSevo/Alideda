<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Cart</h2>
                <p class="text-sm text-muted">Review your items</p>
            </div>

            <a href="{{ route('products.index') }}" class="text-secondary hover:underline">
                ← Continue shopping
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded-lg p-3 text-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($items->isEmpty())
                <div class="bg-surface border border-border rounded-xl shadow p-6">
                    <p class="text-muted">Your cart is empty.</p>
                </div>
            @else
                <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-background border-b border-border">
                                <tr class="text-left">
                                    <th class="py-3 px-4">Item</th>
                                    <th class="py-3 px-4">Price</th>
                                    <th class="py-3 px-4">Qty</th>
                                    <th class="py-3 px-4">Total</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php
                                        $p = $item->product;
                                        $thumb = $p?->primaryImage
                                            ? ($p->primaryImage?->path ?? asset('images/no-image.jpg'))
                                            : asset('images/no-image.jpg');

                                        $lineTotal = (float) $p->price * (int) $item->quantity;
                                    @endphp

                                    <tr class="border-b border-border">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $thumb }}"
                                                    class="w-16 h-16 rounded-lg border border-border object-cover" alt="thumb">

                                                <div>
                                                    <a href="{{ route('products.show', $p) }}"
                                                        class="text-text font-semibold hover:underline">
                                                        {{ $p->name }}
                                                    </a>
                                                    <div class="text-sm text-muted">
                                                        @if($p->store)
                                                            Store:
                                                            <a class="text-secondary hover:underline"
                                                                href="{{ route('stores.show', $p->store) }}">
                                                                {{ $p->store->name }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-3 px-4 text-text">
                                            ${{ number_format((float) $p->price, 2) }}
                                        </td>

                                        <td class="py-3 px-4">
                                            <form method="POST" action="{{ route('cart.update', $p) }}"
                                                class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')

                                                <input type="number" name="quantity" min="0" max="999"
                                                    value="{{ $item->quantity }}"
                                                    class="w-24 border border-border rounded-md px-3 py-2 bg-surface text-text">

                                                <button
                                                    class="bg-primary text-white px-3 py-2 rounded-md hover:bg-primary-dark">
                                                    Update
                                                </button>
                                                <a href="{{ route('cart.index') }}"
                                                    class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                                                    Cancel
                                                </a>
                                            </form>
                                            <p class="text-xs text-muted mt-1">Set to 0 to remove</p>
                                        </td>

                                        <td class="py-3 px-4 font-semibold text-text">
                                            ${{ number_format($lineTotal, 2) }}
                                        </td>

                                        <td class="py-3 px-4 text-right">
                                            <form method="POST" action="{{ route('cart.remove', $p) }}"
                                                onsubmit="return confirm('Remove this item?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="text-danger hover:underline">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2"></div>

                    <div class="bg-surface border border-border rounded-xl shadow p-6">
                        <h3 class="font-semibold text-text text-lg">Summary</h3>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-muted">Subtotal</span>
                            <span class="text-text font-semibold">
                                ${{ number_format((float) $subtotal, 2) }}
                            </span>
                        </div>

                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-muted">Shipping</span>
                            <span class="text-text">—</span>
                        </div>

                        <div class="mt-4 border-t border-border pt-4 flex items-center justify-between">
                            <span class="text-text font-semibold">Total</span>
                            <span class="text-text font-bold text-xl">
                                ${{ number_format((float) $subtotal, 2) }}
                            </span>
                        </div>

                        <a href="{{ route('checkout.create') }}"
                            class="mt-5 block w-full text-center bg-accent text-white px-4 py-3 rounded-lg hover:bg-accent-dark">
                            Checkout
                        </a>

                        <p class="text-xs text-muted text-center mt-2">
                            Payment will be added later.
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
