<?php

namespace App\Entities\Repositories\Category;

interface SubCategorySortOptionsRepositoryInterface
{
    public function store(array $options, int $subCategoryId): int;
}
