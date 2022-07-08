<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'verified'        => $this->hasVerifiedEmail(),
            'business'        => new UserBusinessInformationResource($this->whenLoaded('businessProfile')),
            'profile'         => new UserProfileResource($this->whenLoaded('profile')),
            'profile_picture' => $this->whenLoaded('pictures', function () {
                return $this->pictures->last();
            }),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
