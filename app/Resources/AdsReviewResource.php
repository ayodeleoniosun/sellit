<?php

namespace App\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AdsReviewResource extends JsonResource
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
            'rating' => $this->rating,
            'comment' => ucfirst($this->comment),
            'buyer_id' => $this->buyer_id,
            'buyer' => User::find($this->buyer_id)->fullname(),
            'created_at' => Carbon::parse($this->created_at)->format('F jS, Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('F jS, Y'),
        ];
    }
}
