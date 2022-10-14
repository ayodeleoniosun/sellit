<?php

namespace App\Contracts\Repositories\Category;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface SubCategoryRepositoryInterface
{
    public function index(Request $request, int $categoryId): LengthAwarePaginator;

    public function allSortOptions(Request $request): Collection;

    public function sortOptionValues(Request $request, int $sortOptionId): Collection;

    public function subCategorySortOptions(int $subCategoryId): Collection;

    public function getSubCategory(string $slug): SubCategory|null;

    public function storeSortOptions(array $options, SubCategory $subCategory): int;

    public function updateSortOptions(array $options, int $subCategoryId): array;
}
