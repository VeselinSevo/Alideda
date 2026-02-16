<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // ownership check
        if (!auth()->user()->stores()->whereKey($product->store_id)->exists()) {
            abort(403);
        }

        $data = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'primary_index' => 'nullable|integer|min:0',
        ]);

        $files = $request->file('images', []);
        $primaryIndex = (int) ($data['primary_index'] ?? 0);

        // Upload all
        $created = [];
        foreach ($files as $i => $file) {
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'alideda/products',
            ]);

            $url = $upload->getSecurePath();

            $created[] = ProductImage::create([
                'product_id' => $product->id,
                'path' => $url,       // URL
                'is_primary' => false,
            ]);
        }

        // If product has no primary yet -> make first overall primary
        if (!$product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        // If they selected primary among NEW uploads -> set it as primary
        if (count($created) > 0) {
            $chosen = $created[$primaryIndex] ?? null;
            if ($chosen) {
                $product->images()->update(['is_primary' => false]);
                $chosen->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Images uploaded.');
    }

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

    public function destroy(ProductImage $image)
    {
        $product = $image->product;

        if (!auth()->user()->stores()->whereKey($product->store_id)->exists()) {
            abort(403);
        }

        $wasPrimary = $image->is_primary;

        // We only delete DB record (Cloudinary file stays).
        // If you want Cloudinary delete, you must store public_id on upload.
        $image->delete();

        if ($wasPrimary) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image deleted.');
    }
}