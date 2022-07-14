<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'id'         => $this->id,
            'state'      => State::where('id', $this->state_id)->select('id', 'name')->first(),
            'city'       => City::where('id', $this->city_id)->select('id', 'name')->first(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
