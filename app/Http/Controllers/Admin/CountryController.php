<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->paginate(20)->withQueryString();
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        // normalize (case-insensitive unique)
        $request->merge([
            'name' => $this->normalizeName($request->input('name')),
            'code' => $this->normalizeCode($request->input('code')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('countries', 'name')],
            'code' => ['required', 'string', 'size:2', Rule::unique('countries', 'code')],
        ]);

        Country::create($data);

        return redirect()->route('admin.countries.index')->with('success', 'Country created.');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->merge([
            'name' => $this->normalizeName($request->input('name')),
            'code' => $this->normalizeCode($request->input('code')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('countries', 'name')->ignore($country->id)],
            'code' => ['required', 'string', 'size:2', Rule::unique('countries', 'code')->ignore($country->id)],
        ]);

        $country->update($data);

        return redirect()->route('admin.countries.index')->with('success', 'Country updated.');
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('admin.countries.index')->with('success', 'Country deleted.');
    }

    private function normalizeName(?string $name): ?string
    {
        if ($name === null)
            return null;
        $name = trim($name);
        $name = preg_replace('/\s+/', ' ', $name);
        return mb_strtolower($name, 'UTF-8');
    }

    private function normalizeCode(?string $code): ?string
    {
        if ($code === null)
            return null;
        $code = trim($code);
        return mb_strtolower($code, 'UTF-8'); // store as lowercase
    }
}