<?php

namespace App\Repositories;

use App\Models\SortOption;
use App\Models\SubCategorySortOption;
use App\Repositories\Interfaces\SubCategorySortOptionsRepositoryInterface;

class SubCategorySortOptionsRepository implements SubCategorySortOptionsRepositoryInterface
{
    private  SubCategorySortOption $subCategorySortOption;

    private  SortOption $sortOption;

    public function __construct(SubCategorySortOption $subCategorySortOption, SortOption $sortOption)
    {
        $this->subCategorySortOption = $subCategorySortOption;
        $this->sortOption = $sortOption;
    }

    public function store(array $options, int $subCategoryId): int
    {
        $sortOptionIds = $this->sortOption->whereIn('name', $options)->pluck('id')->toArray();

        $subCategorySortOptionIds = $this->sortOption->join('sub_category_sort_options', function ($join) use ($subCategoryId) {
            $join->on('sort_options.id', '=', 'sub_category_sort_options.sort_option_id')
            ->where('sub_category_sort_options.sub_category_id', $subCategoryId);
        })->pluck('sub_category_sort_options.sort_option_id')->toArray();

        $newSortOptionIds = array_values(array_diff($sortOptionIds, $subCategorySortOptionIds));

        $counter = 0;

        foreach ($newSortOptionIds as $option) {
            $this->subCategorySortOption->create([
                'sub_category_id' => $subCategoryId,
                'sort_option_id' => $option
            ]);

            $counter++;
        }

        return $counter;
    }
}
