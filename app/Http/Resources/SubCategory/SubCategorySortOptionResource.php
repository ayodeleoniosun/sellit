<?php

namespace App\Http\Resources\SubCategory;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'sort_option'  => $this->whenLoaded('sortOption'),
        ];
    }
}
