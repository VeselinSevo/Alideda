<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="font-semibold text-xl text-text leading-tight">
                Edit Product
            </h2>
            <a href="{{ route('products.show', $product) }}" class="text-secondary hover:underline">
                ← Back to Product
            </a>
        </div>
    </x-slot>

    @php
        $isOwner = auth()->check()
            && auth()->user()->stores()->whereKey($product->store_id)->exists();
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-4 text-danger">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <p class="text-success mb-4">{{ session('success') }}</p>
            @endif

            {{-- =========================
            UPDATE PRODUCT FORM
            ==========================--}}
            <div class="bg-surface shadow sm:rounded-lg p-6 mb-8">

                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-medium">Name</label>
                        <input
                            class="bg-surface text-text border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                            name="name" value="{{ old('name', $product->name) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Description</label>
                        <textarea
                            class="bg-surface text-text border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                            name="description">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Price</label>
                        <input
                            class="bg-surface text-text border-border focus:border-primary focus:ring-primary rounded-md shadow-sm w-full px-3 py-2"
                            name="price" value="{{ old('price', $product->price) }}">
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('products.show', $product) }}"
                            class="bg-background border border-border text-text px-4 py-2 rounded-lg hover:opacity-90">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-dark">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>


            {{-- =========================
            IMAGE MANAGEMENT
            ==========================--}}
            @if($isOwner)
                <div class="bg-surface border border-border rounded shadow p-6">

                    <h3 class="font-semibold text-text mb-4">Manage Images</h3>

                    {{-- Upload form (SEPARATE FORM — NOT NESTED) --}}
                    <form method="POST" action="{{ route('products.images.store', $product) }}"
                        enctype="multipart/form-data" class="mb-6">
                        @csrf

                        <div class="mb-3">
                            <label class="block font-medium">Upload Images</label>
                            <input id="editImagesInput" class="border rounded w-full px-3 py-2" type="file" name="images[]"
                                multiple accept="image/*">

                            <input type="hidden" name="primary_index" id="editPrimaryIndex" value="0">

                            <p class="text-sm text-muted mt-1">
                                After selecting, click thumbnail to choose primary.
                            </p>
                        </div>

                        <div id="editPreviewGrid" class="flex flex-wrap gap-3 mb-3"></div>

                        <button type="submit" class="bg-accent text-white px-4 py-2 rounded hover:bg-accent-dark">
                            Upload Images
                        </button>
                    </form>

                    {{-- Existing images --}}
                    <h4 class="font-medium text-text mb-3">Current Images</h4>

                    @if($product->images->isEmpty())
                        <p class="text-muted">No images yet.</p>
                    @else
                        <div class="flex flex-wrap gap-4">
                            @foreach($product->images as $img)
                                    <div class="w-28">

                                        {{-- Set primary --}}
                                        <form method="POST" action="{{ route('product-images.primary', $img) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="w-28 h-28 rounded border overflow-hidden
                                                                                                            {{ $img->is_primary
                                ? 'border-accent ring-2 ring-accent'
                                : 'border-border hover:border-secondary' }}">
                                                <img src="{{ $img->path ?? asset('images/no-image.jpg') }}" class="w-28 h-28 object-cover"
                                                    alt="img">
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('product-images.destroy', $img) }}"
                                            onsubmit="return confirm('Delete this image?')" class="mt-2">
                                            @csrf
                                            @method('DELETE')

                                            <button class="w-full bg-danger text-white px-2 py-1 rounded hover:opacity-90">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                            @endforeach
                        </div>

                        <p class="text-sm text-muted mt-3">
                            Tip: Click an image to set as primary.
                        </p>
                    @endif

                </div>
            @endif

        </div>
    </div>
</x-app-layout>


{{-- =========================
PREVIEW SCRIPT
==========================--}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const input = document.getElementById('editImagesInput');
        const grid = document.getElementById('editPreviewGrid');
        const primaryIndex = document.getElementById('editPrimaryIndex');

        let selectedIndex = 0;

        function render(files) {
            grid.innerHTML = '';

            if (!files || files.length === 0) return;

            Array.from(files).forEach((file, idx) => {

                const url = URL.createObjectURL(file);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className =
                    'w-28 h-28 rounded border overflow-hidden ' +
                    (idx === selectedIndex
                        ? 'border-accent ring-2 ring-accent'
                        : 'border-border');

                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-full h-full object-cover';

                btn.appendChild(img);

                btn.addEventListener('click', () => {
                    selectedIndex = idx;
                    primaryIndex.value = idx;
                    render(input.files);
                });

                grid.appendChild(btn);
            });
        }

        input.addEventListener('change', (e) => {
            selectedIndex = 0;
            primaryIndex.value = '0';
            render(e.target.files);
        });

    });
</script>
