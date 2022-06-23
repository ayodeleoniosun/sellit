<?php

namespace App\Resources;

use App\Models\ActiveStatus;
use App\Models\Ads;
use App\Models\SortOption;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdsSortOptionResource extends JsonResource
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
            'sort_option_id' => $this->sort_option_id,
            'value' => $this->value,
            'ads_name' => Ads::find($this->ads_id)->name,
            'sort_option' => SortOption::find($this->sort_option_id)->name,
            'sort_option_value' => DB::table(SortOption::find($this->sort_option_id)->name)->where([
                'id' => $this->value,
                'active_status' => ActiveStatus::ACTIVE
            ])->value('name'),
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
            'status' => ActiveStatus::find($this->active_status)->name,
        ];
    }
}
