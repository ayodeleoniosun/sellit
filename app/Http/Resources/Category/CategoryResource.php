<?php

namespace App\Http\Resources\Category;

use App\Models\ActiveStatus;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'icon'           => Storage::disk('s3')->url($this->file->path),
            'sub_categories' => $this->whenLoaded('subCategories'),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
