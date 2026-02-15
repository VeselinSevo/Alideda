<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Optional: admin can do anything
        if ($user->is_admin) {
            return $next($request);
        }

        // ----- STORE CHECK (for routes with {store}) -----
        $storeParam = $request->route('store');
        if ($storeParam) {
            $store = $storeParam instanceof Store ? $storeParam : Store::find($storeParam);

            if (!$store) {
                abort(404);
            }

            $ownsStore = $user->stores()->whereKey($store->id)->exists();
            if (!$ownsStore) {
                abort(403);
            }
        }

        // ----- PRODUCT CHECK (for routes with {product}) -----
        $productParam = $request->route('product');
        if ($productParam) {
            $product = $productParam instanceof Product ? $productParam : Product::find($productParam);

            if (!$product) {
                abort(404);
            }

            // user must own the store that owns this product
            $ownsStore = $user->stores()->whereKey($product->store_id)->exists();
            if (!$ownsStore) {
                abort(403);
            }
        }

        return $next($request);
    }
}
