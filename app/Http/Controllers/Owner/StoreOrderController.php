<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StoreOrder;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function index(Request $request)
    {
        $userStoreIds = auth()->user()->stores()->pluck('id');

        $status = (string) $request->query('status', '');
        $q = trim((string) $request->query('q', ''));

        $storeOrders = StoreOrder::query()
            ->with(['order.user', 'store'])
            ->whereIn('store_id', $userStoreIds)
            ->when($status !== '', fn($qb) => $qb->where('status', $status))
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    if (ctype_digit($q)) {
                        $w->orWhere('order_id', (int) $q);
                    }
                    $w->orWhereHas('order.user', fn($u) => $u->where('email', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('owner.orders.index', compact('storeOrders', 'statuses', 'status', 'q'));
    }

    public function show(StoreOrder $storeOrder)
    {
        abort_unless(auth()->user()->stores()->whereKey($storeOrder->store_id)->exists(), 403);

        $storeOrder->load(['order.user', 'items.product', 'store']);

        $statuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('owner.orders.show', compact('storeOrder', 'statuses'));
    }

    public function updateStatus(Request $request, StoreOrder $storeOrder, OrderStatusService $orderStatusService)
    {
        abort_unless(auth()->user()->stores()->whereKey($storeOrder->store_id)->exists(), 403);

        $data = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,completed,cancelled'],
        ]);

        $storeOrder->update(['status' => $data['status']]);

        // ✅ Recalculate the main order status based on all store orders
        $storeOrder->loadMissing('order');
        $orderStatusService->recalculate($storeOrder->order);

        return back()->with('success', 'Status updated.');
    }
}