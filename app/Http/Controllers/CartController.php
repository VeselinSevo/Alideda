<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Http\Requests\CartUpdateRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = auth()->user()
            ->cart()
            ->with(['items.product.primaryImage', 'items.product.store'])
            ->first();

        $items = $cart?->items ?? collect();

        $subtotal = $items->sum(function ($item) {
            return (float) $item->product->price * (int) $item->quantity;
        });

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Product $product, Request $request, CartService $cartService)
    {
        $qty = (int) $request->input('qty', 1);
        $cartService->add(auth()->user(), $product, $qty);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Added to cart.');
    }

    public function update(Product $product, CartUpdateRequest $request, CartService $cartService)
    {
        $cartService->updateQty(auth()->user(), $product, (int) $request->quantity);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function remove(Product $product, CartService $cartService)
    {
        $cartService->remove(auth()->user(), $product);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed.');
    }
}