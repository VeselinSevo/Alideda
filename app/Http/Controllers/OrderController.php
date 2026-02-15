<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $statuses = ['placed', 'pending', 'processing', 'shipped', 'completed', 'cancelled'];

        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $sort = (string) $request->query('sort', 'created_at');
        $dir = strtolower((string) $request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['created_at', 'total', 'status', 'id'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('id', $q) // works if q is numeric
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('full_name', 'like', "%{$q}%");
                });
            })
            ->when($status !== '' && in_array($status, $statuses, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'q', 'status', 'sort', 'dir', 'statuses'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load([
            'items.product.primaryImage',
            'country',
        ]);

        return view('orders.show', compact('order'));
    }
}