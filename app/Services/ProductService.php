<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(array $attributes): Product
    {
        $categories = $attributes['categories'];
        unset($attributes['categories']);

        $product = $this->productRepository->create($attributes);
        $product->categories()->sync($categories);

        return $product;
    }

    public function update(Product $product, array $attributes): bool
    {
        $this->productRepository->updateCategories($product, $attributes['categories']);

        return $this->productRepository->update($product, $attributes);
    }

    public function findByFilter(array $filters): Collection
    {
        return $this->productRepository->findByFilterQuery($filters)->get();
    }
}
