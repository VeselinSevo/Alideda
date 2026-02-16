<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\Country;
use App\Queries\ProductsQuery;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $base = Product::query()
            ->with(['store.country', 'primaryImage']);

        $products = ProductsQuery::for($base)
            ->apply($request)
            ->paginate(12)
            ->withQueryString();

        $stores = Store::orderBy('name')->get(['id', 'name']);
        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('products.index', compact('products', 'stores', 'countries'));
    }

    public function create(Request $request)
    {
        $stores = auth()->user()->stores()->orderBy('name')->get();

        $selectedStoreId = $request->query('store_id');
        if ($selectedStoreId && !$stores->where('id', $selectedStoreId)->count()) {
            abort(403);
        }

        return view('products.create', compact('stores', 'selectedStoreId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'store_id' => 'required|integer',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'primary_index' => 'nullable|integer|min:0',
        ]);

        // Must own store
        if (!auth()->user()->stores()->whereKey($data['store_id'])->exists()) {
            abort(403);
        }

        $product = Product::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'store_id' => $data['store_id'],
        ]);

        $files = $request->file('images', []);
        $primaryIndex = (int) ($data['primary_index'] ?? 0);
        var_dump($files);

        foreach ($files as $i => $file) {
            $upload = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'alideda/products',
            ]);

            $url = $upload->getSecurePath(); // full https url

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $url,                 // store URL now
                'is_primary' => ($i === $primaryIndex),
            ]);
        }

        // If they uploaded images but none is primary (edge case), set first as primary
        if (count($files) > 0 && !$product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['store', 'images']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'store']);
        $stores = auth()->user()->stores()->orderBy('name')->get();

        return view('products.edit', compact('product', 'stores'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
        ]);

        $product->update($data);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        // You can optionally delete Cloudinary assets if you store public_id.
        // For now, keep it simple: delete DB images and product.
        $product->images()->delete();
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted!');
    }
}