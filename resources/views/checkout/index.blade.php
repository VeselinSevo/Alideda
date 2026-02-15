<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">Checkout</h2>
                <p class="text-sm text-muted">Enter delivery details</p>
            </div>

            <a href="{{ route('cart.index') }}" class="text-secondary hover:underline">
                ← Back to Cart
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-background">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 bg-surface border border-border rounded-lg p-4 text-danger">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-surface border border-border rounded-xl shadow p-6">
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text">Full name</label>
                            <input name="full_name" value="{{ old('full_name', auth()->user()->name) }}"
                                class="mt-1 w-full border border-border rounded-lg px-3 py-2 bg-surface text-text">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text">Email</label>
                            <input name="email" value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 w-full border border-border rounded-lg px-3 py-2 bg-surface text-text">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-text">Phone (optional)</label>
                        <input name="phone" value="{{ old('phone') }}"
                            class="mt-1 w-full border border-border rounded-lg px-3 py-2 bg-surface text-text">
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-text">Address</label>
                        <input name="address" value="{{ old('address') }}"
                            class="mt-1 w-full border border-border rounded-lg px-3 py-2 bg-surface text-text">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-text">City</label>
                            <input name="city" value="{{ old('city') }}"
                                class="mt-1 w-full border border-border rounded-lg px-3 py-2 bg-surface text-text">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text">Country (optional)</label>
                            <select name="country_id"
                                class="mt-1 w-full border border-border rounded-lg px-3 py-2 bg-surface text-text">
                                <option value="">—</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-6 w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary-dark">
                        Place order
                    </button>

                    <p class="text-xs text-muted text-center mt-2">
                        This creates an order and clears your cart.
                    </p>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>