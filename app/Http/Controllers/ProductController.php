<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductImage;

use App\Models\Store;
use App\Models\Country;
use App\Queries\ProductsQuery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function index(Request $request)
    {
        $base = Product::query()
            ->with(['store.country', 'primaryImage']); // you already have primaryImage

        $products = ProductsQuery::for($base)
            ->apply($request)
            ->paginate(12)
            ->withQueryString();

        $stores = Store::orderBy('name')->get(['id', 'name']);
        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('products.index', compact('products', 'stores', 'countries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'store_id' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'primary_index' => 'nullable|integer|min:0',
        ]);

        // must own store (you already have this)
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

        foreach ($files as $i => $file) {
            $path = $file->store('products', 'public'); // storage/app/public/products/...

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => ($i === $primaryIndex),
            ]);
        }

        // if they uploaded images but primary index was out of range, make first primary
        if (count($files) > 0 && !$product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }

        return redirect()->route('products.show', $product)->with('success', 'Product created successfully.');
    }


    public function show(Product $product)
    {
        $product->load(['store', 'images']);
        return view('products.show', compact('product'));
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

    public function edit(Product $product)
    {
        // $breadcrumbs = [
        //     [
        //         'label' => 'Products',
        //         'url' => route('products.index'),
        //     ],
        //     [
        //         'label' => $product->name, // or $product->id
        //         'url' => route('products.show', $product),
        //     ],
        //     [
        //         'label' => 'Edit',
        //         'url' => '',
        //     ],
        // ];
        $product->load(['images', 'store']);
        $stores = auth()->user()->stores()->orderBy('name')->get(); // if you also allow changing store
        return view('products.edit', compact('product', 'stores'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted!');
    }


    public function create(Request $request)
    {
        $stores = auth()->user()->stores()->orderBy('name')->get();

        // Optional preselected store from query (?store_id=)
        $selectedStoreId = $request->query('store_id');

        // SECURITY: only allow preselect if user owns that store
        if ($selectedStoreId && !$stores->where('id', $selectedStoreId)->count()) {
            abort(403);
        }

        return view('products.create', compact('stores', 'selectedStoreId'));
    }

}

// create ce vratiti formu frontu koja ce da sadrzi inpute koji kada se popune i submituju onda ce se gadjati endpoint koji ce zvati store i store ce upisati u bazu



