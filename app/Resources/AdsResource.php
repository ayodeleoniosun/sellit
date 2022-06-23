<?php

namespace App\Resources;

use App\Models\ActiveStatus;
use App\Models\AdsPicture;
use App\Models\AdsSortOption;
use App\Models\Category;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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

        $total_rating = ($sum_rating > 0) ? round(($sum_rating / $count_reviews) * 5, 1) : 5.0;

        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'seller_id' => $this->seller_id,
            'name' => ucfirst($this->name),
            'slug' => $this->slug,
            'category_slug' => Category::find($this->category_id)->slug,
            'category' => ucfirst(Category::find($this->category_id)->name),
            'sub_category' => ucfirst(SubCategory::find($this->sub_category_id)->name) ?? null,
            'seller' => UserResource::collection(
                User::where([
                    'id' => $this->seller_id,
                    'active_status' => ActiveStatus::ACTIVE
                ])->get()
            ),
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
            'total_rating' => $total_rating,
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
