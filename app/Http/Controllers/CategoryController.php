<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\AddCategoryRequest;
use App\Http\Requests\Category\AddSubCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\UpdateSubCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\SubCategoryCollection;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryServiceInterface $category;

    public function __construct(CategoryServiceInterface $category)
    {
        $this->category = $category;
    }

    public function index(Request $request): CategoryCollection
    {
       return $this->category->index($request);
    }

    public function subCategories(Request $request): SubCategoryCollection
    {
        return $this->category->subCategories($request);
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

    public function addSubCategory(AddSubCategoryRequest $request): JsonResponse
    {
        $response = $this->category->addSubCategory($request->validated());
        return response()->success($response, 'Sub category successfully added');
    }

    public function updateSubCategory(UpdateSubCategoryRequest $request, $slug): JsonResponse
    {
        $data = array_merge($request->validated(), ['slug' => $slug]);
        $response = $this->category->updateSubCategory($data);
        return response()->success($response, 'Sub category successfully updated');
    }
}
