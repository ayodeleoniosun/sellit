<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Ads;
use App\Modules\Api\V1\Models\File;
use App\Modules\Api\V1\Models\SubCategory;
use Illuminate\Support\Carbon;
use App\Modules\Api\V1\Resources\AdsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => ucfirst($this->name),
            'icon' => File::find($this->file_id)->filename,
            'status' => ActiveStatus::find($this->active_status)->name,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
            
            'sub_categories' => SubCategory::where([
                'category_id' => $this->id,
                'active_status' => ActiveStatus::ACTIVE
            ])->with('sortOptions')->get(),

            'ads' => AdsResource::collection(Ads::where([
                'category_id' => $this->id,
                'active_status' => ActiveStatus::ACTIVE
            ])->get())
        ];
    }
}
