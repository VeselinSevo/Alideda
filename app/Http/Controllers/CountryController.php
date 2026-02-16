<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get();
        return view('countries.index', compact('countries'));
    }

    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        // ✅ normalize BEFORE validate (case-insensitive uniqueness)
        $request->merge([
            'name' => $this->normalizeName($request->input('name')),
            'code' => $this->normalizeCode($request->input('code')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('countries', 'name')],
            'code' => ['required', 'string', 'size:2', Rule::unique('countries', 'code')],
        ]);

        Country::create($data);

        return redirect()
            ->route('countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        // ✅ normalize BEFORE validate (case-insensitive uniqueness)
        $request->merge([
            'name' => $this->normalizeName($request->input('name')),
            'code' => $this->normalizeCode($request->input('code')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('countries', 'name')->ignore($country->id)],
            'code' => ['required', 'string', 'size:2', Rule::unique('countries', 'code')->ignore($country->id)],
        ]);

        $country->update($data);

        return redirect()
            ->route('countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()
            ->route('countries.index')
            ->with('success', 'Country deleted successfully.');
    }

    private function normalizeName(?string $name): ?string
    {
        if ($name === null)
            return null;

        // trim + collapse spaces + lowercase
        $name = trim($name);
        $name = preg_replace('/\s+/', ' ', $name);

        // mb_strtolower to be safe with non-ascii
        return mb_strtolower($name, 'UTF-8');
    }

    private function normalizeCode(?string $code): ?string
    {
        if ($code === null)
            return null;

        // trim + lowercase; always store as lowercase in DB
        $code = trim($code);
        $code = mb_strtolower($code, 'UTF-8');

        return $code;
    }
}