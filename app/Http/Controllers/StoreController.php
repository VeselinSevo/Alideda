<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Queries\StoresQuery;

class StoreController extends Controller
{

    public function index(Request $request)
    {
        $base = Store::query()
            ->with('country')
            ->withCount('products');

        $stores = StoresQuery::for($base)
            ->apply($request)
            ->paginate(12)
            ->withQueryString();

        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('stores.index', compact('stores', 'countries'));
    }

    public function show(Store $store)
    {
        $store->load(['country', 'products.primaryImage']);
        return view('stores.show', compact('store'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        return view('stores.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[0-9\\-\\s()+]+$/'],
        ]);

        Store::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'verified' => false, // or true for dev
        ]);

        return redirect()->route('stores.index')->with('success', 'Store created successfully.');
    }

    public function edit(Store $store)
    {
        $countries = Country::orderBy('name')->get();
        return view('stores.edit', compact('store', 'countries'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[0-9\\-\\s()+]+$/'],
        ]);

        $store->update($request->only([
            'name',
            'address',
            'city',
            'country_id',
            'phone',
        ]));

        return redirect()
            ->route('stores.show', $store)
            ->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }

    public function myStores()
    {
        $stores = Store::withCount('products')
            ->where('user_id', auth()->id())
            ->latest()->paginate(9);

        return view('stores.my-stores', compact('stores'));
    }

}
