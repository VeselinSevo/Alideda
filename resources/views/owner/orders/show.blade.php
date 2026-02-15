<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">
                    Store Order #{{ $storeOrder->id }}
                </h2>
                <p class="text-sm text-muted">
                    Store: {{ $storeOrder->store?->name ?? '—' }} •
                    Customer: {{ $storeOrder->order?->user?->email ?? '—' }}
                </p>
            </div>

            <a href="{{ route('owner.orders.index') }}" class="text-secondary underline">← Back</a>
        </div>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded p-3 text-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 bg-surface border border-border rounded-xl shadow p-4 overflow-x-auto">
                    <h3 class="font-semibold text-text mb-3">Items</h3>

                    <table class="min-w-full">
                        <thead class="bg-background border-b border-border">
                            <tr class="text-left">
                                <th class="py-2 px-3">Product</th>
                                <th class="py-2 px-3">Qty</th>
                                <th class="py-2 px-3">Price</th>
                                <th class="py-2 px-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($storeOrder->items as $item)
                                <tr class="border-b border-border">
                                    <td class="py-2 px-3">
                                        @if($item->product)
                                            <a class="text-secondary underline"
                                                href="{{ route('products.show', $item->product) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Deleted product</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3">{{ $item->quantity }}</td>
                                    <td class="py-2 px-3">${{ number_format((float) $item->price, 2) }}</td>
                                    <td class="py-2 px-3 font-semibold">
                                        ${{ number_format((float) ($item->price * $item->quantity), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-surface border border-border rounded-xl shadow p-4">
                        <h3 class="font-semibold text-text mb-3">Status</h3>

                        <form method="POST" action="{{ route('owner.orders.status', $storeOrder) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <select name="status" class="w-full bg-surface border-border rounded px-3 py-2">
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" @selected($storeOrder->status === $st)>{{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="flex justify-end gap-2">
                                <a href="{{ route('owner.orders.index') }}"
                                    class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                                    Cancel
                                </a>
                                <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-surface border border-border rounded-xl shadow p-4">
                        <h3 class="font-semibold text-text mb-3">Summary</h3>

                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Items</span>
                            <span class="text-text">{{ $storeOrder->items->sum('quantity') }}</span>
                        </div>

                        <div class="flex justify-between text-sm mt-2">
                            <span class="text-muted">Subtotal</span>
                            <span
                                class="text-text font-semibold">${{ number_format((float) $storeOrder->subtotal, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
