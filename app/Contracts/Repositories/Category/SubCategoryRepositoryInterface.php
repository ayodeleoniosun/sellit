<?php

namespace App\Contracts\Repositories\Category;

use App\Models\SubCategory;

interface SubCategoryRepositoryInterface
{
    public function store(array $data): SubCategory;

    public function getSubCategory(string $slug): ?SubCategory;

    public function update(array $data, SubCategory $subCategory): SubCategory;
}
