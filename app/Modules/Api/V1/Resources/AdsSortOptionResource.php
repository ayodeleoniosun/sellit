<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Ads;
use App\Modules\Api\V1\Models\SortOption;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
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
