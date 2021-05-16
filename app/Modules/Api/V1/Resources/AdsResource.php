<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\AdsPicture;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\SubCategory;
use App\Modules\Api\V1\Models\User;
use App\Modules\Api\V1\Models\Review;
use App\Modules\Api\V1\Models\AdsSortOption;
use Illuminate\Support\Carbon;
use App\Modules\Api\V1\Resources\AdsSortOptionResource;
use App\Modules\Api\V1\Resources\AdsPictureResource;
use App\Modules\Api\V1\Resources\AdsReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $count_reviews = (Review::where([
            'ads_id' => $this->id,
            'active_status' => ActiveStatus::ACTIVE
        ])->count()) * 5;

        $sum_rating = Review::where([
            'ads_id' => $this->id,
            'active_status' => ActiveStatus::ACTIVE
        ])->sum('rating');

        $total_rating = round(($sum_rating / $count_reviews) * 5, 1);

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'seller_id' => $this->seller_id,
            'name' => ucfirst($this->name),
            'slug' => $this->slug,
            'category_slug' => Category::find($this->category_id)->slug,
            'category' => ucfirst(Category::find($this->category_id)->name),
            'sub_category' => ucfirst(SubCategory::find($this->category_id)->name) ?? null,
            'seller' => User::find($this->seller_id)->fullname(),
            'seller_phone_number' => User::find($this->seller_id)->phone_number,
            'seller_address' => User::find($this->seller_id)->business_address,
            'description' => ucfirst($this->description),
            'price' => $this->price,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y'),
            'status' => ActiveStatus::find($this->active_status)->name,
            'reviews' => AdsReviewResource::collection(
                Review::where([
                    'ads_id' => $this->id,
                    'active_status' => ActiveStatus::ACTIVE
                ])->latest()->get()
            ),
            'total_ratings' => $total_rating,
            'pictures' => AdsPictureResource::collection(
                AdsPicture::where([
                    'ads_id' => $this->id,
                    'active_status' => ActiveStatus::ACTIVE
                ])->latest()->get()
            ),
            'sort_options' => AdsSortOptionResource::collection(
                AdsSortOption::where([
                    'ads_id' => $this->id,
                    'active_status' => ActiveStatus::ACTIVE
                ])->get()
            )
        ];
    }
}
