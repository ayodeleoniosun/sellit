<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\AddCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryServiceInterface $category;

    public function __construct(CategoryServiceInterface $category)
    {
        $this->category = $category;
    }

    public function store(AddCategoryRequest $request): JsonResponse
    {
        $response = $this->category->store($request->validated());
        return response()->success($response, 'Category successfully added');
    }

    public function update(UpdateCategoryRequest $request, $slug): JsonResponse
    {
        $data = array_merge($request->validated(), ['slug' => $slug]);
        $response = $this->category->update($data);
        return response()->success($response, 'Category successfully updated');
    }
}
