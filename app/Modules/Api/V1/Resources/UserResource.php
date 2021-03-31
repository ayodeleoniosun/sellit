<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\File;
use App\Modules\Api\V1\Models\State;
use App\Modules\Api\V1\Models\City;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => ucfirst($this->first_name),
            'last_name' => ucfirst($this->last_name),
            'email_address' => $this->email_address,
            'phone_number' => $this->phone_number,
            'business_name' => ucfirst($this->business_name),
            'business_slug' => $this->business_slug,
            'business_description' => ucfirst($this->business_description),
            'state_id' => $this->state_id,
            'state' => ucfirst(State::find($this->state_id)->name) ?? null,
            'city_id' => $this->city_id,
            'city' => ucfirst(City::find($this->city_id)->name) ?? null,
            'business_address' => $this->business_address,
            'profile_picture' => File::find($this->file_id)->filename,
            'status' => ActiveStatus::find($this->active_status)->name,
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y, h:i A'),
        ];
    }
}
