<?php

namespace App\Repositories\Interfaces;

use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;

interface SubCategoryRepositoryInterface
{
    public function store(array $data): SubCategoryResource;

    public function getSubCategory(string $slug): ?SubCategory;

    public function update(array $data, SubCategory $subCategory): SubCategoryResource;
}
