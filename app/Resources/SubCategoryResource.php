<?php

namespace App\Resources;

use App\Models\ActiveStatus;
use App\Models\Ads;
use App\Models\Category;
use App\Models\SubCategorySortOption;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => ucfirst($this->name),
            'slug' => $this->slug,
            'category' => Category::find($this->category_id)->name,
            'status' => ActiveStatus::find($this->active_status)->name,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
            'sort_options' => SubCategorySortOption::where('sub_category_id', $this->id)->get(),
            'ads' => AdsResource::collection(Ads::where([
                'sub_category_id' => $this->id,
                'active_status' => ActiveStatus::ACTIVE
            ])->get())
        ];
    }
}
