<?php

namespace App\Contracts\Repositories\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function index(Request $request): LengthAwarePaginator;

    public function store(array $data): Category;

    public function update(array $data, Category $category): Category;

    public function getCategory(string $slug): ?Category;
}
