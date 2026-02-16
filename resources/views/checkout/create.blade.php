<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">
                    Checkout
                </h2>
                <p class="text-sm text-muted">
                    Complete your order
                </p>
            </div>

            <a href="{{ route('cart.index') }}" class="text-secondary hover:underline">
                ← Back to Cart
            </a>
        </div>
    </x-slot>

    @php
        $cart = auth()->user()->cart()->with('items.product.primaryImage')->first();
        $items = $cart?->items ?? collect();

        $subtotal = $items->sum(
            fn($item) =>
            (float) $item->product->price * (int) $item->quantity
        );
    @endphp

    <div class="py-8 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 bg-danger/10 border border-danger text-danger rounded p-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- LEFT: Checkout Form --}}
                <div class="lg:col-span-7">
                    <div class="bg-surface border border-border rounded-xl shadow p-6">

                        <form method="POST" action="{{ route('checkout.store') }}">
                            @csrf

                            <h3 class="text-lg font-semibold text-text mb-4">
                                Billing Details
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-medium text-text">
                                        Full Name
                                    </label>
                                    <input name="full_name" value="{{ old('full_name', auth()->user()->name) }}"
                                        class="mt-1 w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-text">
                                        Email
                                    </label>
                                    <input name="email" value="{{ old('email', auth()->user()->email) }}"
                                        class="mt-1 w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-text">
                                        Phone
                                    </label>
                                    <input name="phone" value="{{ old('phone') }}"
                                        class="mt-1 w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-text">
                                        City
                                    </label>
                                    <input name="city" value="{{ old('city') }}"
                                        class="mt-1 w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-text">
                                        Address
                                    </label>
                                    <input name="address" value="{{ old('address') }}"
                                        class="mt-1 w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-text">
                                        Country
                                    </label>
                                    <select name="country_id"
                                        class="mt-1 w-full border-border rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="">Select country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark">
                                    Place Order
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                {{-- RIGHT: Order Summary --}}
                <div class="lg:col-span-5">
                    <div class="bg-surface border border-border rounded-xl shadow p-6 sticky top-6">

                        <h3 class="text-lg font-semibold text-text mb-4">
                            Order Summary
                        </h3>

                        @if($items->isEmpty())
                            <p class="text-muted">Your cart is empty.</p>
                        @else

                            <div class="space-y-4">

                                @foreach($items as $item)
                                    @php
                                        $product = $item->product;
                                        $img = $product->primaryImage
                                            ? ($product->primaryImage?->path ?? asset('images/no-image.jpg'))
                                            : asset('images/no-image.jpg');
                                    @endphp

                                    <div class="flex gap-4 items-center border-b border-border pb-4">
                                        <img src="{{ $img }}" class="w-16 h-16 object-cover rounded border border-border">

                                        <div class="flex-1">
                                            <p class="text-text font-medium">
                                                {{ $product->name }}
                                            </p>
                                            <p class="text-sm text-muted">
                                                Qty: {{ $item->quantity }}
                                            </p>
                                        </div>

                                        <div class="text-text font-semibold">
                                            ${{ number_format($product->price * $item->quantity, 2) }}
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <div class="mt-6 border-t border-border pt-4 space-y-2">
                                <div class="flex justify-between text-text">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-lg font-bold text-text">
                                    <span>Total</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>

                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
