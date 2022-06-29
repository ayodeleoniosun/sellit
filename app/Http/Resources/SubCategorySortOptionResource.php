<?php

namespace App\Http\Resources;

use App\Models\ActiveStatus;
use App\Models\SortOption;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SubCategorySortOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'sub_category' => $this->whenLoaded('subCategory'),
            'sort_option'  => $this->whenLoaded('sortOption'),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
