<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Store Orders</h2>
        <p class="text-sm text-muted">Orders for your stores</p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

            <div class="flex justify-end items-center mb-4 gap-3 flex-wrap">
                <form method="GET" class="flex gap-2 flex-wrap items-center">
                    <input name="q" value="{{ $q }}" placeholder="Search order id or customer email"
                        class="bg-surface text-text border-border rounded-md px-3 py-2 w-72">

                    <select name="status" class="bg-surface text-text border-border rounded-md px-3 py-2">
                        <option value="">All statuses</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" @selected($status === $st)>{{ $st }}</option>
                        @endforeach
                    </select>

                    <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                        Filter
                    </button>

                    <a href="{{ route('owner.orders.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </form>
            </div>

            <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-background border-b border-border">
                        <tr class="text-left">
                            <th class="py-3 px-4">Store</th>
                            <th class="py-3 px-4">Order</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Subtotal</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($storeOrders as $so)
                            <tr class="border-b border-border">
                                <td class="py-3 px-4 font-medium">{{ $so->store?->name ?? '—' }}</td>
                                <td class="py-3 px-4">#{{ $so->order_id }}</td>
                                <td class="py-3 px-4 text-muted">{{ $so->order?->user?->email ?? '—' }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded bg-background border border-border text-sm">
                                        {{ $so->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-semibold">${{ number_format((float) $so->subtotal, 2) }}</td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('owner.orders.show', $so) }}"
                                        class="bg-secondary text-white px-3 py-1 rounded hover:bg-secondary-dark">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 px-4 text-muted">No store orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $storeOrders->links() }}
            </div>

        </div>
    </div>
</x-app-layout>