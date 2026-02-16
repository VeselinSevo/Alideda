<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    /**
     * Display list of countries (read-only).
     */
    public function index()
    {
        $countries = Country::orderBy('name')->get();

        return view('countries.index', compact('countries'));
    }
}