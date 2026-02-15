<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrdersQuery
{
    public function __construct(private Builder $query)
    {
    }

    public static function for(Builder $query): self
    {
        return new self($query);
    }

    public function apply(Request $request): Builder
    {
        $q = trim((string) $request->query('q', ''));
        $status = $request->query('status'); // string|null
        $sort = $request->query('sort', 'latest');

        $this->query
            ->when($q !== '', function (Builder $qb) use ($q) {
                // Search by order id or user email
                $qb->where(function (Builder $w) use ($q) {
                    if (ctype_digit($q)) {
                        $w->orWhere('id', (int) $q);
                    }

                    $w->orWhereHas('user', function (Builder $uq) use ($q) {
                        $uq->where('email', 'like', "%{$q}%");
                    });
                });
            })
            ->when($status !== null && $status !== '', function (Builder $qb) use ($status) {
                $qb->where('status', $status);
            });

        switch ($sort) {
            case 'total_asc':
                $this->query->orderBy('total', 'asc');
                break;
            case 'total_desc':
                $this->query->orderBy('total', 'desc');
                break;
            case 'oldest':
                $this->query->oldest();
                break;
            case 'latest':
            default:
                $this->query->latest();
                break;
        }

        return $this->query;
    }
}