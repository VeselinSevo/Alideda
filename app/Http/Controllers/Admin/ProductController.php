<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\Country;
use App\Queries\ProductsQuery;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $base = Product::query()
            ->with(['store.country', 'primaryImage']);

        $products = ProductsQuery::for($base)
            ->apply($request)
            ->paginate(15)
            ->withQueryString();

        $stores = Store::orderBy('name')->get(['id', 'name']);
        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('admin.products.index', compact('products', 'stores', 'countries'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }
}

