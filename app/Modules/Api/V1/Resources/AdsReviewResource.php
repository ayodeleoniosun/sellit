<?php

namespace App\Modules\Api\V1\Resources;

use App\Modules\Api\V1\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
