<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Category;
use App\Modules\Api\V1\Models\SubCategory;
use App\Modules\Api\V1\Models\User;
use App\Modules\Api\V1\Models\AdsSortOption;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
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
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'seller_id' => $this->seller_id,
            'name' => ucfirst($this->name),
            'category' => Category::find($this->category_id)->name,
            'sub_category' => SubCategory::find($this->category_id)->name,
            'user' => User::find($this->seller_id)->fullname(),
            'description' => ucfirst($this->description),
            'price' => $this->price,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
            'status' => ActiveStatus::find($this->active_status)->name,
            'sort_options' => AdsSortOption::where('ads_id', $this->id)->get()
        ];
    }
}
