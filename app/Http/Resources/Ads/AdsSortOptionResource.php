<?php

namespace App\Http\Resources\Ads;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsSortOptionResource extends JsonResource
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
            'sort_option' => $this->sort_option_values_id
        ];
    }
}
