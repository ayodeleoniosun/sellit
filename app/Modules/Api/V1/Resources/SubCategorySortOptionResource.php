<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\SubCategory;
use App\Modules\Api\V1\Models\SortOption;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategorySortOptionResource extends JsonResource
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
            'sub_category_id' => $this->sub_category_id,
            'sub_category' => SubCategory::find($this->sub_category_id)->name,
            'sort_option_id' => $this->sort_option_id,
            'sort_option' => SortOption::find($this->sort_option_id)->name,
            'status' => ActiveStatus::find($this->active_status)->name,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
        ];
    }
}
