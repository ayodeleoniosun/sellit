<?php

namespace App\Services\Interfaces;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\SubCategory\SubCategoryCollection;
use App\Http\Resources\SubCategory\SubCategoryResource;
use App\Http\Resources\SubCategory\SubCategorySortOptionResource;
use Illuminate\Http\Request;

interface CategoryServiceInterface
{
    public function index(Request $request): CategoryCollection;

    public function subCategories(Request $request): SubCategoryCollection;

    public function store(array $data): CategoryResource;

    public function storeSortOptions(array $data, int $subCategoryId): int;

    public function update(array $data): CategoryResource;

    public function addSubCategory(array $data): SubCategoryResource;

    public function updateSubCategory(array $data): SubCategoryResource;
}
