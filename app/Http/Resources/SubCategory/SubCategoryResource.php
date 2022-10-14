<?php

namespace App\Http\Resources\SubCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'name'         => ucfirst($this->name),
            'slug'         => $this->slug,
            'category'     => $this->whenLoaded('category'),
            'sort_options' => $this->whenLoaded('sortOptions'),
        ];
    }
}
