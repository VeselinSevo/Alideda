<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="font-semibold text-xl text-text leading-tight">
                Create Product
            </h2>
            <a href="{{ route('products.index') }}" class="text-secondary hover:underline">
                ← Back to Products
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
                @if($stores->isEmpty())
                    <p class="text-text">
                        You don’t have any stores yet.
                        <a class="text-secondary underline" href="{{ route('stores.create') }}">Create a store</a>
                        first.
                    </p>
                @else
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-medium">Store</label>
                            <select
                                class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                                name="store_id">
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" @selected(old('store_id', $selectedStoreId) == $store->id)>
                                        {{ $store->name }}

                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">Name</label>
                            <input
                                class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                                name="name" value="{{ old('name') }}">
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">Description</label>
                            <textarea
                                class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                                name="description">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium">Price</label>
                            <input
                                class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                                name="price" value="{{ old('price') }}">
                        </div>

                        {{-- images preview --}}
                        <div class="mb-4">
                            <label class="block font-medium">Images</label>
                            <input id="imagesInput"
                                class="bg-surface text-text placeholder-muted border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                                type="file" name="images[]" multiple accept="image/*">

                            <p class="text-sm text-muted mt-1">
                                Upload multiple images. Click a thumbnail to choose the primary image.
                            </p>

                            <input type="hidden" name="primary_index" id="primaryIndex"
                                value="{{ old('primary_index', 0) }}">

                            <div id="previewGrid" class="mt-3 flex flex-wrap gap-3"></div>
                        </div>

                        <div class="mt-6 flex justify-end gap-2">
                            <a href="{{ route('products.index') }}"
                                class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                                Cancel
                            </a>
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                                Submit
                            </button>
                        </div>
                    </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('imagesInput');
        const grid = document.getElementById('previewGrid');
        const primaryIndexInput = document.getElementById('primaryIndex');

        let selectedIndex = parseInt(primaryIndexInput.value || '0', 10);

        function renderPreviews(files) {
            grid.innerHTML = '';

            if (!files || files.length === 0) {
                return;
            }

            // If selected index out of range, fallback to 0
            if (selectedIndex >= files.length) {
                selectedIndex = 0;
                primaryIndexInput.value = '0';
            }

            Array.from(files).forEach((file, index) => {
                const url = URL.createObjectURL(file);

                const wrapper = document.createElement('button');
                wrapper.type = 'button';
                wrapper.className =
                    'w-24 h-24 rounded border object-cover overflow-hidden focus:outline-none ' +
                    (index === selectedIndex ? 'border-accent ring-2 ring-accent' : 'border-border');

                wrapper.title = index === selectedIndex ? 'Primary image' : 'Set as primary';

                const img = document.createElement('img');
                img.src = url;
                img.alt = 'preview';
                img.className = 'w-24 h-24 object-cover';

                wrapper.addEventListener('click', () => {
                    selectedIndex = index;
                    primaryIndexInput.value = String(index);

                    // rerender borders
                    renderPreviews(input.files);
                });

                wrapper.appendChild(img);
                grid.appendChild(wrapper);
            });
        }

        input.addEventListener('change', (e) => {
            selectedIndex = 0;
            primaryIndexInput.value = '0';
            renderPreviews(e.target.files);
        });

        // If form reloads with old input, we can't rebuild file previews (browser security).
    });
</script>
