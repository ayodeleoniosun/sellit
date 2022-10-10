<?php

namespace App\Contracts\Repositories\Category;

use App\Models\SubCategory;

interface SubCategoryRepositoryInterface
{
    public function getSubCategory(string $slug): SubCategory|null;

    public function storeSortOptions(array $options, int $subCategoryId): int;

    public function updateSortOptions(array $options, int $subCategoryId): array;
}
