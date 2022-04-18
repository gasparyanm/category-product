<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(ProductRequest $request)
    {
        $products = $this->productService->findByFilter($request->all());

        return ProductResource::collection($products);
    }

    public function store(
        ProductCreateRequest $request,
        DatabaseManager $databaseManager
    ): JsonResponse
    {
        $databaseManager->transaction(function () use ($request): void {
            $this->productService->create($request->validated());
        });

        return response()->json([
            'msg' => 'ok'
        ], ResponseAlias::HTTP_CREATED);
    }

    public function update(
        ProductCreateRequest $request,
        Product $product,
        DatabaseManager $databaseManager
    ): JsonResponse
    {
        $databaseManager->transaction(function () use ($request, $product): void {
            $this->productService->update($product, $request->validated());
        });

        return response()->json([
            'msg' => 'ok'
        ], ResponseAlias::HTTP_OK);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        $product->categories()->detach();

        return response()->json([
            'msg' => 'ok'
        ], ResponseAlias::HTTP_OK);
    }
}
