<?php

namespace App\Repositories\Interfaces;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function store(array $data): CategoryResource;

    public function update(array $data, Category $category): CategoryResource;

    public function getCategory(string $slug): ?Category;
}
