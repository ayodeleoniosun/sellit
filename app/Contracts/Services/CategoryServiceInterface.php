<?php

namespace App\Contracts\Services;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\SubCategory\SubCategoryCollection;
use App\Http\Resources\SubCategory\SubCategoryResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface CategoryServiceInterface
{
    public function index(Request $request): CategoryCollection;

    public function allSortOptions(Request $request): Collection;

    public function sortOptionValues(Request $request, int $sortOptionId): Collection;

    public function subCategorySortOptions(Request $request, int $subCategoryId): AnonymousResourceCollection;

    public function subCategories(Request $request, int $categoryId): SubCategoryCollection;

    public function store(array $data): CategoryResource;

    public function update(array $data): CategoryResource;

    public function addSubCategory(array $data): SubCategoryResource;

    public function updateSubCategory(array $data): SubCategoryResource;

    public function storeSortOptions(array $data, int $subCategoryId): int;

    public function updateSortOptions(array $data, int $subCategoryId): array;
}
