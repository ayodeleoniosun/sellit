<?php

namespace App\Http\Resources;

use App\Models\ActiveStatus;
use App\Models\Ads;
use App\Models\File;
use App\Models\SubCategory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => ucfirst($this->name),
            'slug'           => $this->slug,
            'icon'           => Storage::disk('categories')->url($this->file->filename),
            'sub_categories' => $this->whenLoaded('subCategories'),
            'ads'            => $this->whenLoaded('ads'),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
