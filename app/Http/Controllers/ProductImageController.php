<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    // Upload more images to a product
    public function store(Request $request, Product $product)
    {
        // ✅ ownership check: user must own the store that owns this product
        if (!auth()->user()->stores()->whereKey($product->store_id)->exists()) {
            abort(403);
        }

        $data = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'primary_index' => 'nullable|integer|min:0',
        ]);

        $files = $request->file('images', []);
        $primaryIndex = (int) ($data['primary_index'] ?? 0);

        foreach ($files as $i => $file) {
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => false,
            ]);
        }

        // If product has no primary, make first uploaded primary
        if (!$product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        // Optional: if you uploaded images and chose a primary from those uploaded,
        // set primary to that newly uploaded image.
        if (count($files) > 0) {
            $newImages = $product->images()->latest()->take(count($files))->get()->reverse()->values();
            $chosen = $newImages->get($primaryIndex);

            if ($chosen) {
                $product->images()->update(['is_primary' => false]);
                $chosen->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Images uploaded.');
    }

    // Set one image as primary
    public function makePrimary(ProductImage $image)
    {
        $product = $image->product;

        if (!auth()->user()->stores()->whereKey($product->store_id)->exists()) {
            abort(403);
        }

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }

    // Delete image + file from disk
    public function destroy(ProductImage $image)
    {
        $product = $image->product;

        if (!auth()->user()->stores()->whereKey($product->store_id)->exists()) {
            abort(403);
        }

        $wasPrimary = $image->is_primary;
        $path = $image->path;

        $image->delete();

        // delete physical file
        Storage::disk('public')->delete($path);

        // if we deleted primary, set another one
        if ($wasPrimary) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image deleted.');
    }
}