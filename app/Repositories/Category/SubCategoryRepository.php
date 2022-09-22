<?php

namespace App\Repositories\Category;

use App\Contracts\Repositories\Category\SubCategoryRepositoryInterface;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SubCategoryRepository implements SubCategoryRepositoryInterface
{
    private SubCategory $subCategory;

    public function __construct(SubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    public function index(Request $request): LengthAwarePaginator
    {
        return SubCategory::with('category', 'sortOptions')->paginate(10);
    }

    public function getSubCategory(string $slug): ?SubCategory
    {
        $subCategory = $this->subCategory->where('slug', $slug);

        if (!$subCategory->first()) {
            return null;
        }

        return $subCategory->with('category', 'sortOptions')->first();
    }

    public function store(array $data): SubCategory
    {
        return $this->subCategory->create($data);
    }

    public function update(array $data, SubCategory $subCategory): SubCategory
    {
        $subCategory->update($data);
        return $this->getSubCategory($subCategory->slug);
    }
}
