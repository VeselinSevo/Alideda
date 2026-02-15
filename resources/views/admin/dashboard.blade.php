<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Admin Dashboard</h2>
                <p class="text-sm text-muted">Overview & management shortcuts</p>
            </div>

            <div class="hidden sm:flex items-center gap-2">
                <a href="{{ route('stores.index') }}" class="text-secondary hover:underline">Go to site →</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-background">
        {{-- STATS CARDS (correlated buttons inside each) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <a class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark"
                href="{{ route('admin.activity-logs.index') }}">
                Activity Logs
            </a>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5">
                {{-- MESSAGES --}}
                <div class="bg-surface border border-border rounded-xl shadow p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Messages</p>
                            <p class="text-3xl font-bold text-text mt-1">{{ $stats['messages_total'] }}</p>
                            <p class="text-sm text-muted mt-1">
                                All : <span class="text-text font-semibold">{{ $stats['messages_total'] }}</span> |
                                Unread: <span class="text-text font-semibold">{{ $stats['messages_unread'] }}</span>
                            </p>
                        </div>

                        <a href="{{ route('admin.messages.index') }}"
                            class="shrink-0 bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark text-sm">
                            Manage
                        </a>
                    </div>
                </div>
                {{-- COUNTRIES --}}
                <div class="bg-surface border border-border rounded-xl shadow p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Countries</p>
                            <p class="text-3xl font-bold text-text mt-1">{{ $stats['countries_total'] }}</p>
                        </div>

                        <a href="{{ route('admin.countries.index') }}"
                            class="shrink-0 bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark text-sm">
                            Manage
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- USERS --}}
                <div class="bg-surface border border-border rounded-xl shadow p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Users</p>
                            <p class="text-3xl font-bold text-text mt-1">{{ $stats['users_total'] }}</p>
                            <p class="text-sm text-muted mt-1">
                                Banned: <span class="text-text font-semibold">{{ $stats['users_banned'] }}</span>
                            </p>
                        </div>

                        <a href="{{ route('admin.users.index') }}"
                            class="shrink-0 bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark text-sm">
                            Manage
                        </a>
                    </div>
                </div>

                {{-- STORES --}}
                <div class="bg-surface border border-border rounded-xl shadow p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Stores</p>
                            <p class="text-3xl font-bold text-text mt-1">{{ $stats['stores_total'] }}</p>
                            <p class="text-sm text-muted mt-1">
                                Verified / Pending shown in chart ↓
                            </p>
                        </div>

                        <a href="{{ route('admin.stores.index') }}"
                            class="shrink-0 bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark text-sm">
                            Manage
                        </a>
                    </div>
                </div>

                {{-- PRODUCTS --}}
                <div class="bg-surface border border-border rounded-xl shadow p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Products</p>
                            <p class="text-3xl font-bold text-text mt-1">{{ $stats['products_total'] }}</p>
                            <p class="text-sm text-muted mt-1">
                                With / without images shown in chart ↓
                            </p>
                        </div>

                        <a href="{{ route('admin.products.index') }}"
                            class="shrink-0 bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark text-sm">
                            Manage
                        </a>
                    </div>
                </div>

                {{-- ORDERS --}}
                <div class="bg-surface border border-border rounded-xl shadow p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm text-muted">Orders</p>
                            <p class="text-3xl font-bold text-text mt-1">{{ $stats['orders_total'] }}</p>
                            <p class="text-sm text-muted mt-1">
                                Status breakdown shown in chart ↓
                            </p>
                        </div>

                        <a href="{{ route('admin.orders.index') }}"
                            class="shrink-0 bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary-dark text-sm">
                            Manage
                        </a>
                    </div>
                </div>
            </div>

            {{-- PIE CHARTS --}}
            <div class="bg-surface border border-border rounded-xl shadow p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-text">Insights</h3>
                        <p class="text-sm text-muted">Quick pie charts for main entities</p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- USERS PIE --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-text">Users</p>
                            <a href="{{ route('admin.users.index') }}"
                                class="text-secondary text-sm hover:underline">Manage</a>
                        </div>
                        <div class="mt-3">
                            <canvas id="pieUsers" height="200"></canvas>
                        </div>
                        <p class="mt-3 text-xs text-muted">Active vs banned</p>
                    </div>

                    {{-- STORES PIE --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-text">Stores</p>
                            <a href="{{ route('admin.stores.index') }}"
                                class="text-secondary text-sm hover:underline">Manage</a>
                        </div>
                        <div class="mt-3">
                            <canvas id="pieStores" height="200"></canvas>
                        </div>
                        <p class="mt-3 text-xs text-muted">Verified vs pending</p>
                    </div>

                    {{-- PRODUCTS PIE --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-text">Products</p>
                            <a href="{{ route('admin.products.index') }}"
                                class="text-secondary text-sm hover:underline">Manage</a>
                        </div>
                        <div class="mt-3">
                            <canvas id="pieProducts" height="200"></canvas>
                        </div>
                        <p class="mt-3 text-xs text-muted">With images vs no images</p>
                    </div>

                    {{-- ORDERS PIE --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-text">Orders</p>
                            <a href="{{ route('admin.orders.index') }}"
                                class="text-secondary text-sm hover:underline">Manage</a>
                        </div>
                        <div class="mt-3">
                            <canvas id="pieOrders" height="200"></canvas>
                        </div>
                        <p class="mt-3 text-xs text-muted">Orders by status</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pies = @json($pies);

            // IMPORTANT:
            // We intentionally do NOT set explicit colors, so Chart.js defaults apply.
            // Your app styling is still clean; charts keep default palette.

            function createPie(canvasId, labels, values) {
                const el = document.getElementById(canvasId);
                if (!el) return;

                new Chart(el, {
                    type: 'pie',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    boxHeight: 12,
                                }
                            }
                        }
                    }
                });
            }

            createPie('pieUsers', pies.users.labels, pies.users.values);
            createPie('pieStores', pies.stores.labels, pies.stores.values);
            createPie('pieProducts', pies.products.labels, pies.products.values);
            createPie('pieOrders', pies.orders.labels, pies.orders.values);
        });
    </script>
</x-app-layout>