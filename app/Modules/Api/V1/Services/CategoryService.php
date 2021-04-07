<?php

namespace App\Modules\Api\V1\Services;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\SubCategory;
use App\Modules\Api\V1\Repositories\CategoryRepository;
use App\Modules\Api\V1\Resources\UserResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService implements CategoryRepository
{
    public function index()
    {
        return Category::where('active_status', ActiveStatus::ACTIVE)->get();
    }

    public function categoryDetails(int $id)
    {
        $category = Category::find($id)->where('active_status', ActiveStatus::ACTIVE)->first();
        
        if (!$category) {
            throw new CustomApiErrorResponseHandler("Category not found.");
        }

        return $category;
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

        return $sub_categories;
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

        return $sub_category;
    }
}
