<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Queries\OrdersQuery;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $base = Order::query()->with('user');

        $orders = OrdersQuery::for($base)
            ->apply($request)
            ->paginate(15)
            ->withQueryString();

        // statuses list for dropdown
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'q' => (string) $request->query('q', ''),
            'status' => (string) $request->query('status', ''),
            'sort' => (string) $request->query('sort', 'latest'),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,paid,processing,shipped,completed,cancelled'],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Order status updated.');
    }
}