<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeOrder(User $user, array $checkoutData): Order
    {
        $cart = Cart::with(['items.product.store'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new \RuntimeException('Cart is empty.');
        }

        return DB::transaction(function () use ($user, $checkoutData, $cart) {

            $subtotal = $cart->items->sum(function ($item) {
                return (float) $item->product->price * (int) $item->quantity;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'placed',
                'subtotal' => $subtotal,
                'total' => $subtotal,

                'full_name' => $checkoutData['full_name'],
                'email' => $checkoutData['email'],
                'phone' => $checkoutData['phone'] ?? null,
                'address' => $checkoutData['address'],
                'city' => $checkoutData['city'],
                'country_id' => $checkoutData['country_id'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $product = $item->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,

                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => (int) $item->quantity,
                    'line_total' => (float) $product->price * (int) $item->quantity,

                    'store_id' => $product->store_id,
                    'store_name' => optional($product->store)->name,
                ]);
            }

            app(\App\Services\StoreOrderService::class)->split($order);

            // clear cart
            $cart->items()->delete();

            return $order;
        });
    }
}