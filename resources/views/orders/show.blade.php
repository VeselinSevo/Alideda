<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">
                    Order #{{ $order->id }}
                </h2>
                <p class="text-sm text-muted">
                    Status: {{ ucfirst($order->status) }} • {{ $order->created_at?->format('d M Y') }}
                </p>
            </div>

            <a href="{{ route('orders.index') }}" class="text-secondary hover:underline">
                ← Back to Orders
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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT: Items --}}
                <div class="lg:col-span-8">
                    <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                        <div class="p-4 border-b border-border bg-background">
                            <h3 class="font-semibold text-text">Items</h3>
                        </div>

                        <div class="divide-y divide-border">
                            @foreach($order->items as $item)
                                @php
                                    $product = $item->product;
                                    $thumb = $product?->primaryImage
                                        ? asset('storage/' . $product->primaryImage->path)
                                        : asset('images/no-image.jpg');
                                @endphp

                                <div class="p-4 flex items-center gap-4">
                                    <img src="{{ $thumb }}" class="w-16 h-16 rounded-lg border border-border object-cover"
                                        alt="thumb">

                                    <div class="flex-1">
                                        <p class="font-semibold text-text">
                                            {{ $item->product_name }}
                                        </p>

                                        @if($item->store_id && $item->store_name)
                                            <p class="text-sm text-muted">
                                                Store: {{ $item->store_name }}
                                            </p>
                                        @endif

                                        <p class="text-sm text-muted">
                                            ${{ number_format((float) $item->unit_price, 2) }} × {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <div class="text-right font-semibold text-text">
                                        ${{ number_format((float) $item->line_total, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Summary + Delivery --}}
                <div class="lg:col-span-4">
                    <div class="bg-surface border border-border rounded-xl shadow p-6 sticky top-6">
                        <h3 class="font-semibold text-text">Summary</h3>

                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-muted">Subtotal</span>
                                <span
                                    class="text-text font-semibold">${{ number_format((float) $order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted">Shipping</span>
                                <span class="text-text">—</span>
                            </div>
                            <div class="border-t border-border pt-3 flex justify-between">
                                <span class="text-text font-semibold">Total</span>
                                <span
                                    class="text-text font-bold text-lg">${{ number_format((float) $order->total, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-border pt-4">
                            <h4 class="font-semibold text-text mb-2">Delivery</h4>

                            <p class="text-sm text-text">{{ $order->full_name }}</p>
                            <p class="text-sm text-muted">{{ $order->email }}</p>

                            @if($order->phone)
                                <p class="text-sm text-muted">Phone: {{ $order->phone }}</p>
                            @endif

                            <p class="text-sm text-muted mt-2">
                                {{ $order->address }}, {{ $order->city }}
                            </p>

                            @if($order->country)
                                <p class="text-sm text-muted">{{ $order->country->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>