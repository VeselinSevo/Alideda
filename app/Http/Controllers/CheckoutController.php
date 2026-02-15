<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Country;
use App\Services\OrderService;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = auth()->user()
            ->cart()
            ->with(['items.product'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('success', 'Your cart is empty.');
        }

        $countries = Country::orderBy('name')->get();

        return view('checkout.create', compact('countries'));
    }

    public function store(CheckoutRequest $request, OrderService $orderService)
    {
        $order = $orderService->placeOrder(auth()->user(), $request->validated());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }
}