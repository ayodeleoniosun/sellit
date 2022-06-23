<?php

namespace App\Resources;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ads_id' => $this->ads_id,
            'file_id' => $this->file_id,
            'picture_url' => Storage::disk('ads')->url(File::find($this->file_id)->filename),
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
            'status' => ActiveStatus::find($this->active_status)->name,
        ];
    }
}
