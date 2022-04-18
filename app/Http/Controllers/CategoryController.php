<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    public function store(
        CategoryCreateRequest $request,
        CategoryRepository $categoryRepository
    ): JsonResponse
    {
        $categoryRepository->create($request->validated());

        return response()->json([
            'msg' => 'ok'
        ], ResponseAlias::HTTP_CREATED);
    }

    public function destroy(Category $category)
    {
        if ($category->products->isNotEmpty()) {
            return response()->json([
                'msg' => 'product_attached'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category->delete();

        return response()->json([
            'msg' => 'ok'
        ], ResponseAlias::HTTP_OK);
    }
}
