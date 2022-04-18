<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

class ProductFilter
{
    public function filter(Builder $query, array $filters): Builder
    {
        $query->when(isset($filters['name']), function ($q) use ($filters) {
                return $q->where('name', 'like', '%' . $filters['name'] . '%');
            })
            ->when(isset($filters['categories']), function ($q) use ($filters) {
                return $q->whereHas('categories', function ($categoryQuery) use ($filters) {
                    $categoryQuery->whereIn('id', $filters['categories']);
                });
            })
            ->when(isset($filters['category']), function ($q) use ($filters) {
                return $q->whereHas('categories', function ($categoryQuery) use ($filters) {
                    $categoryQuery->where('name', 'like', '%' . $filters['category'] . '%');
                });
            })
            ->when(isset($filters['price_from']), function ($q) use ($filters) {
                return $q->where('price', '>=', $filters['price_from']);
            })
            ->when(isset($filters['price_to']), function ($q) use ($filters) {
                return $q->where('price', '<=', $filters['price_to']);
            })
            ->when(isset($filters['published']), function ($q) use ($filters) {
                return $q->where('published', (bool)$filters['published']);
            })
            ->when(isset($filters['isDeleted']) && $filters['isDeleted'] === true, function ($q) use ($filters) {
                return $q->withTrashed;
            });

        return $query;
    }
}
