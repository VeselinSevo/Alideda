<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StoresQuery
{
    public function __construct(
        private Builder $query
    ) {
    }

    public static function for(Builder $query): self
    {
        return new self($query);
    }

    public function apply(Request $request): Builder
    {
        $q = trim((string) $request->query('q', ''));
        $country = $request->query('country'); // id
        $verified = $request->query('verified'); // '1' | '0' | null
        $sort = $request->query('sort', 'latest'); // default

        $this->query
            ->when($q !== '', function (Builder $qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%");
            })
            ->when($country, function (Builder $qb) use ($country) {
                $qb->where('country_id', $country);
            })
            ->when($verified !== null && $verified !== '', function (Builder $qb) use ($verified) {
                $qb->where('verified', (bool) ((int) $verified));
            });

        // Sorting
        switch ($sort) {
            case 'name_asc':
                $this->query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $this->query->orderBy('name', 'desc');
                break;

            case 'products_desc':
                // requires withCount('products')
                $this->query->orderBy('products_count', 'desc');
                break;

            case 'country_asc':
                // sort by country name (join)
                $this->query
                    ->leftJoin('countries', 'stores.country_id', '=', 'countries.id')
                    ->orderBy('countries.name', 'asc')
                    ->select('stores.*');
                break;

            case 'latest':
            default:
                $this->query->latest();
                break;
        }

        return $this->query;
    }
}