<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UsersQuery
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
        $admin = $request->query('admin');   // '1' | '0' | null
        $banned = $request->query('banned'); // '1' | '0' | null
        $sort = $request->query('sort', 'latest');

        $this->query
            ->when($q !== '', function (Builder $qb) use ($q) {
                $qb->where(function (Builder $w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($admin !== null && $admin !== '', function (Builder $qb) use ($admin) {
                $qb->where('is_admin', (bool) ((int) $admin));
            })
            ->when($banned !== null && $banned !== '', function (Builder $qb) use ($banned) {
                if ((int) $banned === 1)
                    $qb->whereNotNull('banned_at');
                else
                    $qb->whereNull('banned_at');
            });

        switch ($sort) {
            case 'name_asc':
                $this->query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $this->query->orderBy('name', 'desc');
                break;
            case 'email_asc':
                $this->query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $this->query->orderBy('email', 'desc');
                break;
            case 'latest':
            default:
                $this->query->latest();
                break;
        }

        return $this->query;
    }
}