<?php

namespace App\Repositories\Interfaces;

use App\Models\SubCategory;

interface SubCategoryRepositoryInterface
{
    public function store(array $data): SubCategory;

    public function getSubCategory(string $slug): ?SubCategory;

    public function update(array $data, SubCategory $subCategory): SubCategory;
}
