<?php

namespace App\Repositories;

use App\Filters\ProductFilter;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductRepository
{
    public const MIN_CATEGORY_COUNT = 2;

    private ProductFilter $productFilter;

    public function __construct(ProductFilter $productFilter)
    {
        $this->productFilter = $productFilter;
    }

    public function query(): Builder
    {
        return Product::query();
    }

    public function create(array $data): Product
    {
        return $this->query()->create($data);
    }

    public function updateCategories( Product $product, array $categories): bool
    {
        $this->query()
            ->when(!empty($categories), function ($productQuery) use ($categories, $product) {
                $product->categories()->detach();
                $product->categories()->attach($categories);
            });

        return count($categories) > self::MIN_CATEGORY_COUNT;
    }

    public function update( Product $product, array $attributes): bool
    {
        $this->query()
            ->when(isset($attributes['name']), function ($productQuery) use ($attributes, $product) {
                $product->name = $attributes['name'];
            })
            ->when(isset($attributes['price']), function ($q) use ($attributes, $product) {
                $product->price = $attributes['price'];
            })
            ->when(isset($attributes['published']), function ($q) use ($attributes, $product) {
                $product->published = $attributes['published'];
            });

        return $product->save();
    }

    public function findByFilterQuery(array $filters, array $with = []): Builder
    {
        return $this
            ->productFilter
            ->filter($this->query(), $filters)
            ->with($with);
    }
}
