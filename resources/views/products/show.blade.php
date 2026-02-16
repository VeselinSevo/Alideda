<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-text leading-tight">
                    {{ $product->name }}
                </h2>
                <p class="text-sm text-muted">
                    @if($product->store)
                        Sold by
                        <a class="text-secondary hover:underline" href="{{ route('stores.show', $product->store) }}">
                            {{ $product->store->name }}
                        </a>
                    @else
                        Store: —
                    @endif
                </p>
            </div>

            <a href="{{ route('products.index') }}" class="text-secondary hover:underline">
                ← Back to Products
            </a>
        </div>
    </x-slot>

    @php
        $isOwner = auth()->check()
            && $product->store
            && auth()->user()->stores()->whereKey($product->store_id)->exists();

        $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
        $mainUrl = $primary?->path ?? asset('images/no-image.jpg');
    @endphp

    <div class="py-8 bg-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-surface border border-border rounded p-3 text-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- LEFT: Gallery --}}
                <div class="lg:col-span-7">
                    <div class="bg-surface border border-border rounded-xl shadow p-4">
                        <div class="aspect-[4/3] w-full overflow-hidden rounded-lg border border-border bg-background">
                            <img id="mainProductImage" src="{{ $mainUrl }}" alt="Product image"
                                class="w-full h-full object-cover cursor-pointer">
                        </div>

                        @if($product->images->isNotEmpty())
                            <div class="mt-4 flex flex-wrap gap-3">
                                @php
                                    $orderedImages = $product->images
                                        ->sortByDesc('is_primary')
                                        ->values();
                                @endphp

                                @foreach($orderedImages as $img)

                                    @php
                                        $thumbUrl = $img->path ?? asset('images/no-image.jpg');
                                    @endphp

                                    <button type="button" data-image="{{ $thumbUrl }}"
                                        class="product-thumb w-20 h-20 rounded-lg border overflow-hidden bg-background
                                                                                                                                                                                                                            {{ $img->is_primary ? 'border-accent ring-2 ring-accent' : 'border-border hover:border-secondary' }}">
                                        <img src="{{ $thumbUrl }}" alt="Thumbnail" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                            <p class="mt-2 text-xs text-muted">Tip: Click a thumbnail to preview. Click main image to open
                                full-screen.</p>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="mt-6 bg-surface border border-border rounded-xl shadow p-6">
                        <h3 class="font-semibold text-text text-lg mb-2">Description</h3>

                        @if($product->description)
                            <p class="text-text leading-relaxed">
                                {{ $product->description }}
                            </p>
                        @else
                            <p class="text-muted">No description provided.</p>
                        @endif
                    </div>
                </div>

                {{-- RIGHT: Purchase / Info --}}
                <div class="lg:col-span-5">
                    <div class="bg-surface border border-border rounded-xl shadow p-6 sticky top-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-muted">Price</p>
                                <p class="text-3xl font-bold text-text">
                                    ${{ number_format((float) $product->price, 2) }}
                                </p>
                            </div>

                            @if($isOwner)
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="bg-secondary text-white px-4 py-2 rounded hover:bg-secondary-dark">
                                        Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                        onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-danger text-white px-4 py-2 rounded hover:opacity-90">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 border-t border-border pt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted">Store</span>
                                <span class="text-sm text-text font-medium">
                                    {{ optional($product->store)->name ?? '—' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted">Created</span>
                                <span class="text-sm text-text">
                                    {{ $product->created_at?->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        {{-- Store card --}}
                        @if($product->store)
                            <div class="mt-6 bg-background border border-border rounded-lg p-4">
                                <p class="text-sm text-muted mb-1">Sold by</p>
                                <a href="{{ route('stores.show', $product->store) }}"
                                    class="text-lg font-semibold text-secondary hover:underline">
                                    {{ $product->store->name }}
                                </a>

                                <div class="mt-2 text-sm text-muted">
                                    @if($product->store->address || $product->store->city)
                                        <p>
                                            {{ $product->store->address ?? '—' }}
                                            {{ $product->store->city ? ', ' . $product->store->city : '' }}
                                        </p>
                                    @endif

                                    @if($product->store->phone)
                                        <p>Phone: {{ $product->store->phone }}</p>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('stores.show', $product->store) }}"
                                        class="inline-block bg-primary text-white px-3 py-2 rounded hover:bg-primary-dark text-sm">
                                        View store →
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Optional CTA placeholder (future orders) --}}
                        <div class="mt-6">
                            <form method="POST" action="{{ route('cart.add', $product) }}">
                                @csrf
                                <input type="hidden" name="qty" value="1">

                                <button class="w-full bg-accent text-white px-4 py-3 rounded-lg hover:bg-primary-dark">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<div id="productImageLightbox" class="fixed inset-0 z-50 hidden bg-black/90 p-4 sm:p-8 flex items-center justify-center"
    aria-hidden="true">
    <button id="lightboxClose" type="button"
        class="absolute top-4 right-4 bg-surface/80 text-text border border-border rounded-lg px-3 py-2 hover:bg-surface">
        Close
    </button>

    <button id="lightboxPrev" type="button"
        class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 bg-surface/80 text-text border border-border rounded-full w-10 h-10 hover:bg-surface">
        ←
    </button>

    <img id="lightboxImage" src="" alt="Preview image" class="max-w-[95vw] max-h-[90vh] object-contain rounded-lg">

    <button id="lightboxNext" type="button"
        class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 bg-surface/80 text-text border border-border rounded-full w-10 h-10 hover:bg-surface">
        →
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('mainProductImage');
        const thumbnails = document.querySelectorAll('.product-thumb');
        const lightbox = document.getElementById('productImageLightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxClose = document.getElementById('lightboxClose');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');
        const galleryImages = Array.from(thumbnails).map(btn => btn.getAttribute('data-image'));
        const hasThumbs = galleryImages.length > 0;
        const images = hasThumbs ? galleryImages : [mainImage.src];
        let currentIndex = 0;

        function setMainImage(src) {
            mainImage.src = src;
        }

        function setActiveThumb(index) {
            thumbnails.forEach((thumb, i) => {
                thumb.classList.remove('border-accent', 'ring-2', 'ring-accent');
                thumb.classList.add('border-border');

                if (i === index) {
                    thumb.classList.remove('border-border');
                    thumb.classList.add('border-accent', 'ring-2', 'ring-accent');
                }
            });
        }

        function renderLightbox() {
            lightboxImage.src = images[currentIndex];
            lightboxPrev.classList.toggle('hidden', images.length < 2);
            lightboxNext.classList.toggle('hidden', images.length < 2);
        }

        function openLightbox(index) {
            currentIndex = index;
            renderLightbox();
            lightbox.classList.remove('hidden');
            lightbox.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
        }

        function closeLightbox() {
            lightbox.classList.add('hidden');
            lightbox.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
        }

        function showPrev() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            renderLightbox();
            setMainImage(images[currentIndex]);
            setActiveThumb(currentIndex);
        }

        function showNext() {
            currentIndex = (currentIndex + 1) % images.length;
            renderLightbox();
            setMainImage(images[currentIndex]);
            setActiveThumb(currentIndex);
        }

        thumbnails.forEach((btn, index) => {
            btn.addEventListener('click', function () {
                currentIndex = index;

                // Change main image
                const newSrc = this.getAttribute('data-image');
                setMainImage(newSrc);
                setActiveThumb(index);
            });
        });

        mainImage.addEventListener('click', function () {
            const activeBySrc = images.findIndex(src => src === mainImage.src);
            const activeIndex = activeBySrc >= 0 ? activeBySrc : currentIndex;
            openLightbox(activeIndex);
        });

        lightboxClose.addEventListener('click', closeLightbox);
        lightboxPrev.addEventListener('click', showPrev);
        lightboxNext.addEventListener('click', showNext);

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });

        document.addEventListener('keydown', function (e) {
            if (lightbox.classList.contains('hidden')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft' && images.length > 1) showPrev();
            if (e.key === 'ArrowRight' && images.length > 1) showNext();
        });
    });
</script>
