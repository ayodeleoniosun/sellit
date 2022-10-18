<?php

namespace App\Http\Resources\Ads;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => ucfirst($this->comment),
            'buyer' => $this->whenLoaded('buyer'),
            'ads' => $this->whenLoaded('ads'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
