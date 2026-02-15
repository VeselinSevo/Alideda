<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="font-semibold text-xl text-text leading-tight">
                Edit Store
            </h2>
            <a href="{{ route('stores.show', $store) }}" class="text-secondary hover:underline">
                ← Back to Store
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 text-danger">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-surface shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('stores.update', $store) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium">Store Name</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="name"
                            value="{{ old('name', $store->name) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Country</label>
                        <select class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="country_id">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $store->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">City</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="city"
                            value="{{ old('city', $store->city) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Address</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="address"
                            value="{{ old('address', $store->address) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Phone</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                            name="phone" value="{{ old('phone', $store->phone) }}" pattern="[0-9\\-\\s()+]+" title="Use numbers, spaces, dashes, parentheses, and +">
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('stores.show', $store) }}"
                            class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                            Update
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
