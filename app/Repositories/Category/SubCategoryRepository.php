<?php

namespace App\Repositories\Category;

use App\Contracts\Repositories\Category\SubCategoryRepositoryInterface;
use App\Models\SortOption;
use App\Models\SortOptionValues;
use App\Models\SubCategory;
use App\Models\SubCategorySortOption;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SubCategoryRepository extends BaseRepository implements SubCategoryRepositoryInterface
{
    private SubCategory $subCategory;

    private SubCategorySortOption $subCategorySortOption;

    private SortOption $sortOption;

    private SortOptionValues $sortOptionValues;

    public function __construct(
        SubCategory $subCategory,
        SubCategorySortOption $subCategorySortOption,
        SortOption $sortOption,
        SortOptionValues $sortOptionValues)
    {
        parent::__construct($subCategory);

        $this->subCategory = $subCategory;
        $this->subCategorySortOption = $subCategorySortOption;
        $this->sortOption = $sortOption;
        $this->sortOptionValues = $sortOptionValues;
    }

    public function index(Request $request, int $categoryId): LengthAwarePaginator
    {
        return $this->subCategory->whereCategoryId($categoryId)->with('category')->paginate(10);
    }

    public function allSortOptions(Request $request): Collection
    {
        return $this->sortOption->all();
    }

    public function sortOptionValues(Request $request, int $sortOptionId): Collection
    {
        return $this->sortOptionValues->whereSortOptionId($sortOptionId)->get();
    }

    public function subCategorySortOptions(int $subCategoryId): Collection
    {
        return $this->subCategorySortOption->whereSubCategoryId($subCategoryId)->with('sortOption')->get();
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
        $options = $this->validSortOptions($options);

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

    public function updateSortOptions(array $options, int $subCategoryId): array
    {
        $subCategory = $this->subCategory->find($subCategoryId);

        $options = $this->validSortOptions($options);

        $subCategorySortOptionIds = $this->subCategorySortOptions($subCategoryId)->pluck('sort_option_id')->toArray();

        $sortOptionIdsToBeRemoved = array_values(array_diff($subCategorySortOptionIds, array_intersect($options, $subCategorySortOptionIds)));
        $removed = 0;

        if (count($sortOptionIdsToBeRemoved) > 0) {
            foreach ($sortOptionIdsToBeRemoved as $option) {
                $subCategory->sortOptions()->detach($option);
                $removed++;
            }
        }

        $sortOptionIdsToBeAdded = array_values(array_diff($options, $subCategorySortOptionIds));
        $added = 0;

        if (count($sortOptionIdsToBeAdded) > 0) {
            foreach ($sortOptionIdsToBeAdded as $option) {
                $subCategory->sortOptions()->attach($option);
                $added++;
            }
        }

        return [$removed, $added];
    }

    private function validSortOptions (array $options): array
    {
        return $this->sortOption->whereIn('id', $options)->pluck('id')->toArray();
    }
}
