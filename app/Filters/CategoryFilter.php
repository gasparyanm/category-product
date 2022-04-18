<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

class CategoryFilter
{
    public function filter(Builder $query, array $filters): Builder
    {
        $query->when($filters['code'], function($query) {
            $query->where(new Expression('LOWER(code)'), 'LIKE', "%{$code}%");
        });

        return $query;
    }
}
