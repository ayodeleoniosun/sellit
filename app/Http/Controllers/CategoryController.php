<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\Http\Requests\Category\AddCategoryRequest;
use App\Http\Requests\Category\AddSubCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\Category\UpdateSubCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\SubCategory\SubCategoryCollection;
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

    public function allSortOptions(Request $request):JsonResponse
    {
        $response = $this->category->allSortOptions($request);

        return response()->success($response, '');
    }

    public function sortOptionValues(Request $request, int $sortOptionId): JsonResponse
    {
        $response =  $this->category->sortOptionValues($request, $sortOptionId);

        return response()->success($response, '');
    }

    public function subCategories(Request $request): SubCategoryCollection
    {
        return $this->category->subCategories($request);
    }

    public function store(AddCategoryRequest $request): JsonResponse
    {
        $response = $this->category->store($request->validated());

        return response()->success($response, 'Category successfully added', 201);
    }

    public function update(UpdateCategoryRequest $request, $slug): JsonResponse
    {
        $data = array_merge($request->validated(), compact('slug'));

        $response = $this->category->update($data);

        return response()->success($response, 'Category successfully updated');
    }

    public function addSubCategory(AddSubCategoryRequest $request): JsonResponse
    {
        $response = $this->category->addSubCategory($request->validated());

        return response()->success($response, 'Sub category successfully added', 201);
    }

    public function updateSubCategory(UpdateSubCategoryRequest $request, string $slug): JsonResponse
    {
        $data = array_merge($request->validated(), compact('slug'));

        $response = $this->category->updateSubCategory($data);

        return response()->success($response, 'Sub category successfully updated');
    }

    public function storeSortOptions(Request $request, $subCategoryId): JsonResponse
    {
        $response = $this->category->storeSortOptions($request->all(), $subCategoryId);

        if ($response > 0) {
            return response()->success([], $response.' sort options successfully added', 201);
        }

        return response()->error('No sort options added');
    }

    public function updateSortOptions(Request $request, $subCategoryId): JsonResponse
    {
        $response = $this->category->updateSortOptions($request->all(), $subCategoryId);
        list ($removed, $added) = $response;

        return response()->success([], $removed.' sort options removed and '.$added.' new sort options successfully added');
    }
}
