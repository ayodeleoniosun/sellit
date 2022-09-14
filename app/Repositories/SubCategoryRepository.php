<?php

namespace App\Repositories;

use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use App\Repositories\Interfaces\SubCategoryRepositoryInterface;

class SubCategoryRepository implements SubCategoryRepositoryInterface
{
    private SubCategory $subCategory;

    public function __construct(SubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
    }

    public function getSubCategory(string $slug): ?SubCategory
    {
        $subCategory = $this->subCategory->where('slug', $slug);

        if (!$subCategory->first()) {
            return null;
        }

        return $subCategory->with('category', 'sortOptions')->first();
    }

    public function store(array $data): SubCategoryResource
    {
        $this->subCategory->create($data);
        return new SubCategoryResource($this->getSubCategory($data['slug']));
    }

    public function update(array $data, SubCategory $subCategory): SubCategoryResource
    {
        $subCategory->update($data);
        return new SubCategoryResource($this->getSubCategory($subCategory->slug));
    }
}
