<?php

namespace App\Sorts\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class OwnerSort implements Sort {

    public function __invoke(Builder $query, $descending, string $property) : Builder
    {
        return $query->join('users', 'products.owner_id', 'users.id')
					->orderBy('users.name', $descending ? 'desc' : 'asc');
    }
}
