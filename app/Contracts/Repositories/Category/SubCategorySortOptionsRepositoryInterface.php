<?php

namespace App\Contracts\Repositories\Category;

interface SubCategorySortOptionsRepositoryInterface
{
    public function store(array $options, int $subCategoryId): int;
}
