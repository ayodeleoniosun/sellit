<?php

namespace App\Resources;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => ucfirst($this->name),
            'slug' => $this->slug,
            'icon' => Storage::disk('categories')->url(File::find($this->file_id)->filename),
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
