<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="font-semibold text-xl text-text leading-tight">
                Create Store
            </h2>
            <a href="{{ route('stores.mine') }}" class="text-secondary hover:underline">
                ← Back to My Stores
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
                <form method="POST" action="{{ route('stores.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium">Store Name</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="name" value="{{ old('name') }}">
                    </div>


                    <div class="mb-4">
                        <label class="block font-medium">Country</label>
                        <select class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="country_id">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">City</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="city" value="{{ old('city') }}">
                    </div>


                    <div class="mb-4">
                        <label class="block font-medium">Address</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2" name="address" value="{{ old('address') }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Phone</label>
                        <input class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                            name="phone" value="{{ old('phone') }}" pattern="[0-9\\-\\s()+]+" title="Use numbers, spaces, dashes, parentheses, and +">
                    </div>

                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                        Save
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
