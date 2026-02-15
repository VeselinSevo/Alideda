<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductsQuery
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
        $store = $request->query('store'); // id
        $country = $request->query('country'); // store country id
        $sort = $request->query('sort', 'latest');

        $min = $request->query('min'); // price
        $max = $request->query('max'); // price

        $this->query
            ->when($q !== '', function (Builder $qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%");
            })
            ->when($store, function (Builder $qb) use ($store) {
                $qb->where('store_id', $store);
            })
            ->when($country, function (Builder $qb) use ($country) {
                $qb->whereHas('store', fn($s) => $s->where('country_id', $country));
            })
            ->when($min !== null && $min !== '', fn(Builder $qb) => $qb->where('price', '>=', (float) $min))
            ->when($max !== null && $max !== '', fn(Builder $qb) => $qb->where('price', '<=', (float) $max));

        switch ($sort) {
            case 'price_asc':
                $this->query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $this->query->orderBy('price', 'desc');
                break;

            case 'name_asc':
                $this->query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $this->query->orderBy('name', 'desc');
                break;

            case 'latest':
            default:
                $this->query->latest();
                break;
        }

        return $this->query;
    }
}