<?php

namespace App\Http\Resources;

use App\Models\ActiveStatus;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
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
            'id'              => $this->id,
            'first_name'      => ucfirst($this->first_name),
            'last_name'       => ucfirst($this->last_name),
            'fullname'        => $this->fullname,
            'slug'            => $this->slug,
            'email_address'   => $this->email_address,
            'phone_number'    => $this->phone_number,
            'verified'        => !is_null($this->email_verified_at),
            'state'           => $this->whenLoaded('profile', function () {
                return $this->profile->state;
            }),
            'city'            => $this->whenLoaded('profile', function () {
                return $this->profile->city;
            }),
            'business'        => $this->whenLoaded('businessProfile', function () {
                return [
                    'name'        => ucfirst($this->businessProfile->name),
                    'slug'        => $this->businessProfile->slug,
                    'slug_url'    => $this->businessProfile->slug_url,
                    'description' => ucfirst($this->businessProfile->description),
                    'address'     => ucfirst($this->businessProfile->address),
                ];
            }),
            'profile_picture' => $this->whenLoaded('pictures', function () {
                return $this->pictures->last();
            }),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
