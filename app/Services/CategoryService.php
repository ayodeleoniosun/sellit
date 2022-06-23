<?php

namespace App\Services;

use App\ApiUtility;
use App\Exceptions\CustomApiErrorResponseHandler;
use App\Models\ActiveStatus;
use App\Models\Category;
use App\Models\File;
use App\Models\SortOption;
use App\Models\SubCategory;
use App\Models\SubCategorySortOption;
use App\Repositories\CategoryRepository;
use App\Resources\CategoryResource;
use App\Resources\SubCategoryResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function App\Modules\Api\V1\Services\count;

class CategoryService implements CategoryRepository
{
    public function index()
    {
        return CategoryResource::collection(Category::where('active_status', ActiveStatus::ACTIVE)->get());
    }

    public function addCategory(array $data)
    {
        $category_name = $data['name'];

        $category = Category::where([
            'name' => $category_name,
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if ($category) {
            throw new CustomApiErrorResponseHandler("Category exist. Use a different category name");
        }

        DB::beginTransaction();

        if (isset($data['icon']) && $data['icon']) {
            $icon = $data['icon'];
            $size = ceil($icon->getSize()/1024);

            if ($size > File::MAX_FILESIZE) {
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
        $category->slug = Str::slug($data['name'], "_");
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

        $category = Category::where([
            'name' => $category_name,
            'active_status' => ActiveStatus::ACTIVE
        ])->where('id', '<>', $id)->first();

        if ($category) {
            throw new CustomApiErrorResponseHandler("Category exist. Use a different category name");
        }

        $icon = $data['icon'];
        $size = ceil($icon->getSize()/1024);

        if ($size > File::MAX_FILESIZE) {
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
        $category->slug = Str::slug($data['name'], "_");
        $category->save();

        DB::commit();

        return [
            'category' => $category,
            'message' => 'Category successfully updated.'
        ];
    }

    public function categoryDetails(int $id)
    {
        $category = Category::where([
            'id' => $id,
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if (!$category) {
            throw new CustomApiErrorResponseHandler("Category not found.");
        }

        return new CategoryResource($category);
    }

    public function addSubCategory(array $data)
    {
        $category = Category::where([
            'id' => $data['category_id'],
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if (!$category) {
            throw new CustomApiErrorResponseHandler("Selected category does not exist.");
        }

        $sub_category = SubCategory::where([
            'name' => $data['name'],
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if ($sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category exist. Use a different name");
        }

        DB::beginTransaction();

        $sub_category = new SubCategory();
        $sub_category->name = $data['name'];
        $sub_category->slug = Str::slug($data['name'], "_");
        $sub_category->category_id = $data['category_id'];
        $sub_category->save();

        $sort_options = $data['sort_options'];

        $added_sort_options = [];

        if (count($sort_options) > 0) {
            foreach ($sort_options as $sort_option) {
                $sort_option_id = SortOption::where([
                    'name' => $sort_option,
                    'active_status' => ActiveStatus::ACTIVE
                ])->value('id');

                if ($sort_option_id) {
                    SubCategorySortOption::create([
                        'sub_category_id' => $sub_category->id,
                        'sort_option_id' => $sort_option_id
                    ]);

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
        $category = Category::where([
            'id' => $data['category_id'],
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if (!$category) {
            throw new CustomApiErrorResponseHandler("Selected category does not exist.");
        }

        $sub_category = SubCategory::where([
            'name' => $data['name'],
            'active_status' => ActiveStatus::ACTIVE
        ])->where('id', '<>', $sub_id)->first();

        if ($sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category exist. Use a different name");
        }

        $sub_category = SubCategory::find($sub_id);
        $sub_category->name = $data['name'];
        $sub_category->slug = Str::slug($data['name'], "_");
        $sub_category->category_id = $data['category_id'];
        $sub_category->save();

        return [
            'sub_category' => $sub_category,
            'message' => 'Sub category successfully updated'
        ];
    }

    public function addSubCategorySortOptions(int $sub_id, array $data)
    {
        $sub_category = SubCategory::where([
            'id' => $sub_id,
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if (!$sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category does not exist.");
        }

        $sort_options = $data['sort_options'];

        $added_sort_options = [];

        if (count($sort_options) > 0) {
            foreach ($sort_options as $sort_option_name) {
                $sort_option = SortOption::where([
                    'name' => $sort_option_name,
                    'active_status' => ActiveStatus::ACTIVE
                ])->first();

                if (!$sort_option) {
                    $sort_option = SortOption::create(['name' => $sort_option_name]);
                }

                $sub_category_sort_option = SubCategorySortOption::where([
                    'sub_category_id' => $sub_id,
                    'sort_option_id' => $sort_option->id,
                    'active_status' => ActiveStatus::ACTIVE
                ])->first();

                if (!$sub_category_sort_option) {
                    SubCategorySortOption::create([
                        'sub_category_id' => $sub_id,
                        'sort_option_id' => $sort_option->id
                    ]);

                    $added_sort_options[] = str_replace("_", " ", $sort_option_name);
                }
            }

            if (count($added_sort_options) > 0) {
                return [
                    'message' => count($added_sort_options)." Sort options added to category",
                    'sort_options' => implode(", ", $added_sort_options)
                ];
            } else {
                throw new CustomApiErrorResponseHandler("No sort options added");
            }
        }
    }

    public function subCategories(int $id)
    {
        $sub_categories = SubCategory::where([
            'category_id' => $id,
            'active_status' => ActiveStatus::ACTIVE
        ])->get();

        if ($sub_categories->count() == 0) {
            throw new CustomApiErrorResponseHandler("No sub category found.");
        }

        return SubCategoryResource::collection($sub_categories);
    }

    public function subCategoryDetails(int $id, int $sub_id)
    {
        $sub_category = SubCategory::where([
            'id' => $sub_id,
            'category_id' => $id,
            'active_status' => ActiveStatus::ACTIVE
        ])->first();

        if (!$sub_category) {
            throw new CustomApiErrorResponseHandler("Sub category not found.");
        }

        return new SubCategoryResource($sub_category);
    }
}
