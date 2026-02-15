<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;

use App\Models\Country;
use App\Queries\StoresQuery;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function index(Request $request)
    {
        $base = Store::query()
            ->with(['owner', 'country'])
            ->withCount('products');

        $stores = StoresQuery::for($base)
            ->apply($request)
            ->paginate(15)
            ->withQueryString();

        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('admin.stores.index', compact('stores', 'countries'));
    }
    public function destroy(Store $store)
    {
        $store->delete();
        return back()->with('success', 'Store deleted.');
    }

    public function toggleVerify(Store $store)
    {
        $store->update([
            'verified' => !$store->verified,
        ]);

        return back()->with('success', 'Store verification updated.');
    }
}
