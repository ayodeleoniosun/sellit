<?php

namespace App\Http\Resources\User;

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
            'fullname' => $this->fullname,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'verified' => $this->isVerified(),
            'business' => new UserBusinessInformationResource($this->whenLoaded('businessProfile')),
            'profile' => new UserProfileResource($this->whenLoaded('profile')),
            'profile_picture' => new UserProfilePictureResource($this->picture),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
