<?php

namespace App\Contracts\Services;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\SubCategory\SubCategoryCollection;
use App\Http\Resources\SubCategory\SubCategoryResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface CategoryServiceInterface
{
    public function index(Request $request): CategoryCollection;

    public function allSortOptions(Request $request): Collection;

    public function sortOptionValues(Request $request, int $sortOptionId): Collection;

    public function subCategories(Request $request): SubCategoryCollection;

    public function store(array $data): CategoryResource;

    public function storeSortOptions(array $data, int $subCategoryId): int;

    public function update(array $data): CategoryResource;

    public function addSubCategory(array $data): SubCategoryResource;

    public function updateSubCategory(array $data): SubCategoryResource;
}
