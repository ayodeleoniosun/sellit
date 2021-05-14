<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Ads;
use App\Modules\Api\V1\Models\File;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\SubCategorySortOption;
use Illuminate\Support\Carbon;
use App\Modules\Api\V1\Resources\AdsResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
