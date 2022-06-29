<?php

namespace App\Http\Resources;

use App\Models\ActiveStatus;
use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class AdsPictureResource extends JsonResource
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
            'id'          => $this->id,
            'ads'         => $this->whenLoaded('ads'),
            'picture_url' => Storage::disk('ads')->url($this->file->filename),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
