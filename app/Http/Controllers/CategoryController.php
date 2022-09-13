<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\UploadCategoryIconRequest;
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

    public function store(UploadCategoryIconRequest $request): JsonResponse
    {
        $response = $this->category->store($request);
        return response()->success($response, 'Category successfully added');
    }
}
