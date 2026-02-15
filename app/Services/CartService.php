<?php
namespace App\Services;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function add(User $user, Product $product, int $qty = 1): void
    {
        $cart = $this->getOrCreateCart($user);

        $item = $cart->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['quantity' => 0]
        );

        $item->update(['quantity' => $item->quantity + max(1, $qty)]);
    }

    public function updateQty(User $user, Product $product, int $qty): void
    {
        $cart = $this->getOrCreateCart($user);

        $item = $cart->items()->where('product_id', $product->id)->first();
        if (!$item)
            return;

        if ($qty <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $qty]);
        }
    }

    public function remove(User $user, Product $product): void
    {
        $cart = $this->getOrCreateCart($user);
        $cart->items()->where('product_id', $product->id)->delete();
    }

    public function clear(User $user): void
    {
        $cart = $this->getOrCreateCart($user);
        $cart->items()->delete();
    }
}