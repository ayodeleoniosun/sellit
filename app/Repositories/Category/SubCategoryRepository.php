<?php

namespace App\Repositories\Category;

use App\Contracts\Repositories\Category\SubCategoryRepositoryInterface;
use App\Models\SortOption;
use App\Models\SubCategory;
use App\Models\SubCategorySortOption;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    private SubCategory $subCategory;
    private SubCategorySortOption $subCategorySortOption;
    private SortOption $sortOption;

    public function __construct(SubCategory $subCategory, SubCategorySortOption $subCategorySortOption, SortOption $sortOption)
    {
        parent::__construct($subCategory);

        $this->subCategory = $subCategory;
        $this->subCategorySortOption = $subCategorySortOption;
        $this->sortOption = $sortOption;
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

    public function storeSortOptions(array $options, int $subCategoryId): int
    {
        $subCategorySortOptionIds = $this->sortOption->join('sub_category_sort_options', function ($join) use ($subCategoryId) {
            $join->on('sort_options.id', '=', 'sub_category_sort_options.sort_option_id')
                ->where('sub_category_sort_options.sub_category_id', $subCategoryId);
        })->pluck('sub_category_sort_options.sort_option_id')->toArray();

        $newSortOptionIds = array_values(array_diff($options, $subCategorySortOptionIds));

        if (count($newSortOptionIds) == 0) {
            return 0;
        }

        $subCategory = $this->subCategory->find($subCategoryId);
        $counter = 0;

        foreach ($newSortOptionIds as $option) {
            $subCategory->sortOptions()->attach($option);
            $counter++;
        }

        return $counter;
    }
}
