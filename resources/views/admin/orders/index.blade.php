<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Orders</h2>
        <p class="text-sm text-muted">Admin management</p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

            <div class="flex justify-between items-center mb-4 gap-3 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="text-secondary underline">← Back to Admin</a>

                <form method="GET" class="flex gap-2 flex-wrap items-center">
                    <input name="q" value="{{ $q }}" placeholder="Search by order id or user email"
                        class="bg-surface text-text border-border rounded-md px-3 py-2 w-64">

                    <select name="status" class="bg-surface text-text border-border rounded-md px-3 py-2">
                        <option value="">All statuses</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" @selected($status === $st)>{{ $st }}</option>
                        @endforeach
                    </select>

                    <select name="sort" class="bg-surface text-text border-border rounded-md px-3 py-2">
                        <option value="latest" @selected($sort === 'latest')>Latest</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest</option>
                        <option value="total_desc" @selected($sort === 'total_desc')>Total: high → low</option>
                        <option value="total_asc" @selected($sort === 'total_asc')>Total: low → high</option>
                    </select>

                    <button class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                        Apply
                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </form>
            </div>

            <div class="bg-surface border border-border rounded-xl shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-background border-b border-border">
                        <tr class="text-left">
                            <th class="py-3 px-4">ID</th>
                            <th class="py-3 px-4">Customer</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Created</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b border-border">
                                <td class="py-3 px-4 font-medium">#{{ $order->id }}</td>
                                <td class="py-3 px-4 text-muted">{{ $order->user?->email ?? '—' }}</td>

                                <td class="py-3 px-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded bg-background border border-border text-sm">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 font-semibold">${{ number_format((float) $order->total, 2) }}</td>
                                <td class="py-3 px-4 text-muted">{{ $order->created_at?->format('d M Y H:i') }}</td>

                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="bg-secondary text-white px-3 py-1 rounded hover:bg-secondary-dark">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 px-4 text-muted">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
