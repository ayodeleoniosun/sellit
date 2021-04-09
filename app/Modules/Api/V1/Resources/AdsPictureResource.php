<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Ads;
use App\Modules\Api\V1\Models\File;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'ads_name' => Ads::find($this->ads_id)->name,
            'picture' => File::find($this->file_id)->filename,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
            'status' => ActiveStatus::find($this->active_status)->name,
        ];
    }
}
