<?php

namespace App\Repositories\Interfaces;

use App\Http\Resources\CategoryResource;

interface CategoryRepositoryInterface
{
    public function store(array $data): CategoryResource;

    public function getCategory(string $slug): ?CategoryResource;
}
