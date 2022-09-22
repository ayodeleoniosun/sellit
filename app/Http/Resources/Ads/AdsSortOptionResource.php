<?php

namespace App\Http\Resources\Ads;

use App\Models\ActiveStatus;
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
            'id'           => $this->id,
            'ads'          => $this->whenLoaded('ads'),
            'sort_options' => $this->whenLoaded('sort_option'),
            'value'        => $this->value,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
