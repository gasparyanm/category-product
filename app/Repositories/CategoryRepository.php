<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository
{
    public function query(): Builder
    {
        return Category::query();
    }

    public function create(array $data): Category
    {
        return $this->query()->create($data);
    }
}
