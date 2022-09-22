<?php

namespace App\Repositories\Interfaces;

use App\Models\SubCategorySortOption;

interface SubCategorySortOptionsRepositoryInterface
{
    public function store(array $options, int $subCategoryId): int;
}
