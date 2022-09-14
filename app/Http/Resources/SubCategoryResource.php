<?php

namespace App\Http\Resources;

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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'category'     => new CategoryResource($this->category),
            'name'         => ucfirst($this->name),
            'slug'         => $this->slug,
            'sort_options' => $this->whenLoaded('sortOptions'),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
