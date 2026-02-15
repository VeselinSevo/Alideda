<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">My Orders</h2>
                <p class="text-sm text-muted">Your recent orders</p>
            </div>

            <a href="{{ route('products.index') }}" class="text-secondary hover:underline">
                ← Back to Products
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

            {{-- Filters --}}
            <div class="mb-4 bg-surface border border-border rounded-xl shadow p-4">
                <form method="GET" class="flex flex-wrap gap-2 justify-end items-center">
                    <input name="q" value="{{ $q ?? '' }}" placeholder="Search by order id, email, name..."
                        class="bg-surface text-text border-border rounded-md px-3 py-2 w-72 focus:border-primary focus:ring-primary">

                    <select name="status"
                        class="bg-surface text-text border-border rounded-md px-3 py-2 focus:border-primary focus:ring-primary">
                        <option value="">All statuses</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" @selected(($status ?? '') === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>

                    <select name="sort"
                        class="bg-surface text-text border-border rounded-md px-3 py-2 focus:border-primary focus:ring-primary">
                        <option value="created_at" @selected(($sort ?? 'created_at') === 'created_at')>Date</option>
                        <option value="total" @selected(($sort ?? '') === 'total')>Total</option>
                        <option value="status" @selected(($sort ?? '') === 'status')>Status</option>
                        <option value="id" @selected(($sort ?? '') === 'id')>Order ID</option>
                    </select>

                    <select name="dir"
                        class="bg-surface text-text border-border rounded-md px-3 py-2 focus:border-primary focus:ring-primary">
                        <option value="desc" @selected(($dir ?? 'desc') === 'desc')>Desc</option>
                        <option value="asc" @selected(($dir ?? '') === 'asc')>Asc</option>
                    </select>

                    <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                        Apply
                    </button>

                    <a href="{{ route('orders.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Results --}}
            @if($orders->isEmpty())
                <div class="bg-surface border border-border rounded-xl shadow p-6">
                    <p class="text-muted">No orders found.</p>
                </div>
            @else
                <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-background border-b border-border">
                                <tr class="text-left">
                                    <th class="py-3 px-4">Order</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Total</th>
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b border-border">
                                        <td class="py-3 px-4 font-semibold text-text">
                                            #{{ $order->id }}
                                        </td>

                                        <td class="py-3 px-4">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded bg-background border border-border text-sm text-text">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>

                                        <td class="py-3 px-4 font-semibold text-text">
                                            ${{ number_format((float) $order->total, 2) }}
                                        </td>

                                        <td class="py-3 px-4 text-muted">
                                            {{ $order->created_at?->format('d M Y') }}
                                        </td>

                                        <td class="py-3 px-4 text-right">
                                            <a href="{{ route('orders.show', $order) }}" class="text-secondary hover:underline">
                                                View →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
