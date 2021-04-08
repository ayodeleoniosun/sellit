<?php

namespace App\Modules\Api\V1\Services;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\File;
use App\Modules\Api\V1\Models\SortOption;
use App\Modules\Api\V1\Models\SubCategory;
use App\Modules\Api\V1\Models\SubCategorySortOption;
use App\Modules\Api\V1\Repositories\CategoryRepository;
use App\Modules\Api\V1\Resources\CategoryResource;
use App\Modules\Api\V1\Resources\SubCategoryResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService implements CategoryRepository
{
    public function index()
    {
        return CategoryResource::collection(Category::where('active_status', ActiveStatus::ACTIVE)->get());
    }

    public function addCategory(array $data)
    {
        $category_name = $data['name'];
        
        $category = Category::where(
            [
                'name' => $category_name,
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->first();
        
        if ($category) {
            throw new CustomApiErrorResponseHandler("Category exist. Use a different category name");
        }

        DB::beginTransaction();
        
        if (isset($data['icon']) && $data['icon']) {
            $icon = $data['icon'];
            $size = ceil($icon->getSize()/1024);
            
            if ($size > File::USER_MAX_FILESIZE) {
                throw new CustomApiErrorResponseHandler("Picture should not be more than 5MB.");
            }

            $timestamp = ApiUtility::generateTimeStamp();
            $filename = $timestamp."_".strtolower($category_name);
            $filename = Str::slug($filename, "_");
            $category_icon = "{$filename}.{$icon->clientExtension()}";

            Storage::disk('categories')->put($category_icon, file_get_contents($icon->getRealPath()));

            $file = new File();
            $file->filename = $category_icon;
            $file->type = File::CATEGORY_FILE_TYPE;
            $file->save();
        }

        $category = new Category();
        $category->name = $data['name'];
        $category->file_id = $file->id ?? File::DEFAULT_ID;
        $category->save();

        DB::commit();

        return [
            'category' => $category,
            'message' => 'Category successfully added.'
        ];
    }

    public function updateCategory(int $id, array $data)
    {
        $category_name = $data['name'];
        
        $category = Category::where(
            [
                'name' => $category_name,
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->where('id', '<>', $id)->first();
        
        if ($category) {
            throw new CustomApiErrorResponseHandler("Category exist. Use a different category name");
        }

        $icon = $data['icon'];
        $size = ceil($icon->getSize()/1024);
        
        if ($size > File::USER_MAX_FILESIZE) {
            throw new CustomApiErrorResponseHandler("Picture should not be more than 5MB.");
        }

        $timestamp = ApiUtility::generateTimeStamp();
        $filename = $timestamp."_".strtolower($category_name);
        $filename = Str::slug($filename, "_");
        $category_icon = "{$filename}.{$icon->clientExtension()}";

        Storage::disk('categories')->put($category_icon, file_get_contents($icon->getRealPath()));

        DB::beginTransaction();
        $category = Category::find($id);
        
        if ($category->file_id == 1) {
            $file = new File();
            $file->filename = $category_icon;
            $file->type = File::CATEGORY_FILE_TYPE;
            $file->save();

            $category->file_id = $file->id ?? File::DEFAULT_ID;
        } else {
            $file = File::find($category->file_id);
            $file->filename = $category_icon;
            $file->save();
        }

        $category->name = $data['name'];
        $category->save();

        DB::commit();

        return [
            'category' => $category,
            'message' => 'Category successfully updated.'
        ];
    }

    public function categoryDetails(int $id)
    {
        $category = Category::where(
            [
                'id' => $id,
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->first();
        
        if (!$category) {
            throw new CustomApiErrorResponseHandler("Category not found.");
        }

        return new CategoryResource($category);
    }

    public function addSubCategory(array $data)
    {
        $category = Category::where(
            [
                'id' => $data['category_id'],
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->first();
        
        if (!$category) {
            throw new CustomApiErrorResponseHandler("Selected category does not exist.");
        }

        $sub_category = SubCategory::where(
            [
                'name' => $data['name'],
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->first();
        
        if ($sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category exist. Use a different name");
        }

        DB::beginTransaction();
        
        $sub_category = new SubCategory();
        $sub_category->name = $data['name'];
        $sub_category->category_id = $data['category_id'];
        $sub_category->save();

        $sort_options = $data['sort_options'];
        
        $added_sort_options = [];

        if (count($sort_options) > 0) {
            foreach ($sort_options as $sort_option) {
                $sort_option_id = SortOption::where(
                    [
                        'name' => $sort_option,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->value('id');

                if ($sort_option_id) {
                    SubCategorySortOption::create(
                        [
                            'sub_category_id' => $sub_category->id,
                            'sort_option_id' => $sort_option_id
                        ]
                    );

                    $added_sort_options[] = str_replace("_", " ", $sort_option);
                }
            }
        }

        DB::commit();

        return [
            'sub_category' => $sub_category,
            'message' => 'Sub category successfully added',
            'sort_options' => implode(", ", $added_sort_options)
        ];
    }

    public function updateSubCategory(int $sub_id, array $data)
    {
        $category = Category::where(
            [
                'id' => $data['category_id'],
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->first();
        
        if (!$category) {
            throw new CustomApiErrorResponseHandler("Selected category does not exist.");
        }

        $sub_category = SubCategory::where(
            [
                'name' => $data['name'],
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->where('id', '<>', $sub_id)
        ->first();
        
        if ($sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category exist. Use a different name");
        }

        DB::beginTransaction();
        
        $sub_category = SubCategory::find($sub_id);
        $sub_category->name = $data['name'];
        $sub_category->category_id = $data['category_id'];
        $sub_category->save();

        $current_sort_options = $sub_category->sortOptions->pluck('sort_option_id')->toArray();
        $req_sort_options = $data['sort_options'];
        
        $sort_options = SortOption::whereIn('name', $req_sort_options)
            ->where('active_status', ActiveStatus::ACTIVE)
            ->pluck('id')->toArray();

        $diff_sort_options = ($current_sort_options > $sort_options) ?
            array_diff($current_sort_options, $sort_options) :
            array_diff($sort_options, $current_sort_options);

        $diff_sort_options = array_values($diff_sort_options);

        $added_sort_options = [];
        $removed_sort_options = [];

        if (count($diff_sort_options) > 0) {
            foreach ($diff_sort_options as $sort_option_id) {
                $sort_option = SortOption::where(
                    [
                        'id' => $sort_option_id,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->value('name');
                
                $sub_category_sort_option = SubCategorySortOption::where(
                    [
                        'sub_category_id' => $sub_category->id,
                        'sort_option_id' => $sort_option_id,
                        'active_status' => ActiveStatus::ACTIVE
                    ]
                )->first();

                if ($sub_category_sort_option) {
                    SubCategorySortOption::where(
                        [
                            'sub_category_id' => $sub_category->id,
                            'sort_option_id' => $sort_option_id,
                            'active_status' => ActiveStatus::ACTIVE
                        ]
                    )->update(['active_status' => ActiveStatus::DELETED]);

                    $removed_sort_options[] = str_replace("_", " ", $sort_option);
                } else {
                    SubCategorySortOption::create(
                        [
                            'sub_category_id' => $sub_category->id,
                            'sort_option_id' => $sort_option_id
                        ]
                    );

                    $added_sort_options[] = str_replace("_", " ", $sort_option);
                }
            }
        }

        DB::commit();

        return [
            'sub_category' => $sub_category,
            'message' => 'Sub category successfully updated'
        ];
    }
    
    public function subCategories(int $id)
    {
        $sub_categories = SubCategory::where(
            [
            'category_id' => $id,
            'active_status' => ActiveStatus::ACTIVE
            ]
        )->get();
        
        if ($sub_categories->count() == 0) {
            throw new CustomApiErrorResponseHandler("No sub category found.");
        }

        return SubCategoryResource::collection($sub_categories);
    }

    public function subCategoryDetails(int $id, int $sub_id)
    {
        $sub_category = SubCategory::where(
            [
            'id' => $sub_id,
            'category_id' => $id,
            'active_status' => ActiveStatus::ACTIVE
            ]
        )->first();
        
        if (!$sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category not found.");
        }

        return new SubCategoryResource($sub_category);
    }
}
