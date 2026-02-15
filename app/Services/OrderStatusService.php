<?php
namespace App\Services;

use App\Models\Order;

class OrderStatusService
{
    public function recalculate(Order $order): void
    {
        $statuses = $order->storeOrders()->pluck('status')->unique();

        if ($statuses->count() === 1) {
            $order->update([
                'status' => $statuses->first()
            ]);
            return;
        }

        // Mixed states
        if ($statuses->contains('processing') || $statuses->contains('shipped')) {
            $order->update(['status' => 'processing']);
            return;
        }

        $order->update(['status' => 'pending']);
    }
}