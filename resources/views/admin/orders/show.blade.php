<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Order #{{ $order->id }}</h2>
                <p class="text-sm text-muted">
                    Customer: {{ $order->user?->email ?? '—' }} •
                    Created: {{ $order->created_at?->format('d M Y H:i') }}
                </p>
            </div>

            <a href="{{ route('admin.orders.index') }}" class="text-secondary underline">← Back to Orders</a>
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

                {{-- Left: items --}}
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
                            @foreach($order->items as $item)
                                <tr class="border-b border-border">
                                    <td class="py-2 px-3">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product) }}"
                                                class="text-secondary underline">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Deleted product: {{ $item->product_name }}</span>
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

                {{-- Right: status + totals --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-surface border border-border rounded-xl shadow p-4">
                        <h3 class="font-semibold text-text mb-3">Status</h3>

                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <select name="status"
                                class="w-full bg-background text-text border-border rounded-md px-3 py-2 focus:border-primary focus:ring-primary">
                                @foreach($statuses as $st)
                                    <option value="{{ $st }}" @selected($order->status === $st)>{{ $st }}</option>
                                @endforeach
                            </select>

                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.orders.index') }}"
                                    class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                                    Cancel
                                </a>
                                <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                                    Save
                                </button>
                            </div>
                        </form>

                        <p class="text-xs text-muted mt-2">
                            Changing status affects what customer sees in “My Orders”.
                        </p>
                    </div>

                    <div class="bg-surface border border-border rounded-xl shadow p-4">
                        <h3 class="font-semibold text-text mb-3">Summary</h3>

                        <div class="flex justify-between text-sm">
                            <span class="text-muted">Items</span>
                            <span class="text-text">{{ $order->items->sum('quantity') }}</span>
                        </div>

                        <div class="flex justify-between text-sm mt-2">
                            <span class="text-muted">Total</span>
                            <span class="text-text font-semibold">${{ number_format((float) $order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
