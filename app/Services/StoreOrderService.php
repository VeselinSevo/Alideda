<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use Illuminate\Support\Facades\DB;

class StoreOrderService
{
    /**
     * Split a global Order into per-store orders.
     *
     * Assumptions:
     * - Order has items() relation returning OrderItem models
     * - OrderItem has: store_id, store_name, product_id, product_name, unit_price, quantity, line_total
     * - StoreOrder has: order_id, store_id, store_name, status, subtotal
     * - StoreOrderItem has: store_order_id, product_id, quantity, price (unit price snapshot)
     */
    public function split(Order $order): void
    {
        DB::transaction(function () use ($order) {

            // make sure we have items loaded
            $order->loadMissing('items');

            if ($order->items->isEmpty()) {
                return;
            }

            // Optional: if you re-run split() don’t duplicate
            // If you WANT to allow re-splitting, remove this block.
            if (method_exists($order, 'storeOrders') && $order->storeOrders()->exists()) {
                return;
            }

            // Group items by store_id
            $groups = $order->items->groupBy('store_id');

            foreach ($groups as $storeId => $items) {

                // If some item has no store_id, skip that group
                if (!$storeId) {
                    continue;
                }

                // Subtotal for that store based on item line_total (or unit_price * qty)
                $subtotal = $items->sum(function ($item) {
                    // Prefer line_total if you store it
                    if ($item->line_total !== null) {
                        return (float) $item->line_total;
                    }

                    // fallback
                    return (float) $item->unit_price * (int) $item->quantity;
                });

                $storeName = $items->first()->store_name ?? null;

                // Create store order
                $storeOrder = StoreOrder::create([
                    'order_id' => $order->id,
                    'store_id' => (int) $storeId,
                    'store_name' => $storeName,
                    'status' => 'pending', // store-level status
                    'subtotal' => (float) $subtotal,
                ]);

                // Create store order items
                foreach ($items as $item) {

                    // ✅ FIX: use unit_price (because OrderItem doesn't have "price")
                    $price = $item->unit_price;

                    // Defensive: if unit_price is missing, skip to avoid DB crash
                    if ($price === null) {
                        continue;
                    }

                    StoreOrderItem::create([
                        'store_order_id' => $storeOrder->id,
                        'product_id' => $item->product_id,
                        'quantity' => (int) $item->quantity,
                        'price' => (float) $price, // unit price snapshot
                    ]);
                }
            }
        });
    }
}