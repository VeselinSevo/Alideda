<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Country;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // COUNTS
        $usersTotal = User::count();
        $usersBanned = User::whereNotNull('banned_at')->count();
        $usersActive = max(0, $usersTotal - $usersBanned);

        $storesTotal = Store::count();
        $storesVerified = Store::where('verified', true)->count();
        $storesPending = max(0, $storesTotal - $storesVerified);

        $productsTotal = Product::count();
        $productsWithImg = Product::whereHas('images')->count();
        $productsNoImg = max(0, $productsTotal - $productsWithImg);

        $ordersTotal = Order::count();
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $stats = [
            'users_total' => $usersTotal,
            'users_banned' => $usersBanned,
            'stores_total' => $storesTotal,
            'products_total' => $productsTotal,
            'orders_total' => $ordersTotal,
            'countries_total' => Country::count(),
            'messages_total' => ContactMessage::count(),
            'messages_unread' => ContactMessage::whereNull('read_at')->count(),
        ];

        // PIE DATA (labels + values)
        $pies = [
            'users' => [
                'labels' => ['Active', 'Banned'],
                'values' => [$usersActive, $usersBanned],
            ],
            'stores' => [
                'labels' => ['Verified', 'Pending'],
                'values' => [$storesVerified, $storesPending],
            ],
            'products' => [
                'labels' => ['With Images', 'No Images'],
                'values' => [$productsWithImg, $productsNoImg],
            ],
            'orders' => [
                'labels' => array_map(fn($s) => ucfirst($s), array_keys($ordersByStatus)),
                'values' => array_values($ordersByStatus),
            ],
        ];

        return view('admin.dashboard', compact('stats', 'pies'));
    }
}