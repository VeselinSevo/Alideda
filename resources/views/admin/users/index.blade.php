<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">Users</h2>
        <p class="text-sm text-muted">Ban/unban and manage users</p>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

            <div class="mb-4">
                <a href="{{ route('admin.dashboard') }}" class="text-secondary underline">← Back to Admin</a>
            </div>

            {{-- FILTERS --}}
            <form method="GET" class="mb-6 bg-surface border border-border rounded-xl shadow p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <div>
                        <label class="block text-sm text-muted mb-1">Search</label>
                        <input name="q" value="{{ request('q') }}"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary"
                            placeholder="name or email...">
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Admin</label>
                        <select name="admin"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="">All</option>
                            <option value="1" @selected(request('admin') === '1')>Admins</option>
                            <option value="0" @selected(request('admin') === '0')>Non-admins</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Banned</label>
                        <select name="banned"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="">All</option>
                            <option value="1" @selected(request('banned') === '1')>Banned</option>
                            <option value="0" @selected(request('banned') === '0')>Not banned</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-muted mb-1">Sort</label>
                        <select name="sort"
                            class="w-full rounded-md border-border bg-background text-text focus:border-primary focus:ring-primary">
                            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Latest</option>
                            <option value="name_asc" @selected(request('sort') === 'name_asc')>Name: A → Z</option>
                            <option value="name_desc" @selected(request('sort') === 'name_desc')>Name: Z → A</option>
                            <option value="email_asc" @selected(request('sort') === 'email_asc')>Email: A → Z</option>
                            <option value="email_desc" @selected(request('sort') === 'email_desc')>Email: Z → A</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 flex gap-2">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">Apply</button>
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                        Reset
                    </a>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="bg-surface border border-border rounded-xl shadow p-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-left border-b border-border bg-background">
                            <th class="py-2 px-3">Name</th>
                            <th class="py-2 px-3">Email</th>
                            <th class="py-2 px-3">Admin</th>
                            <th class="py-2 px-3">Banned</th>
                            <th class="py-2 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b border-border">
                                <td class="py-2 px-3">{{ $user->name }}</td>
                                <td class="py-2 px-3">{{ $user->email }}</td>
                                <td class="py-2 px-3">{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                                <td class="py-2 px-3">{{ $user->banned_at ? 'Yes' : 'No' }}</td>
                                <td class="py-2 px-3">
                                    @if(!$user->is_admin)
                                        <div class="flex justify-end gap-2">
                                            @if(!$user->banned_at)
                                                <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                                                    onsubmit="return confirm('Ban this user?')">
                                                    @csrf
                                                    <button class="bg-warning text-white px-3 py-1 rounded hover:opacity-90">
                                                        Ban
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.users.unban', $user) }}"
                                                    onsubmit="return confirm('Unban this user?')">
                                                    @csrf
                                                    <button class="bg-success text-white px-3 py-1 rounded hover:opacity-90">
                                                        Unban
                                                    </button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-danger text-white px-3 py-1 rounded hover:opacity-90">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-right">
                                            <span class="text-muted">—</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
