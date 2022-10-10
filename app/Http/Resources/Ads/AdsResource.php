<?php

namespace App\Http\Resources\Ads;

use App\Models\ActiveStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $countReviews = $this->reviews?->count() * 5;
        $sumRating = $this->reviews?->sum('rating');

        $totalRating = ($sumRating > 0) ? round(($sumRating / $countReviews) * 5, 1) : 0.0;

        return [
            'id'           => $this->id,
            'name'         => ucfirst($this->name),
            'slug'         => $this->slug,
            'description'  => ucfirst($this->description),
            'price'        => $this->price,
            'category'     => $this->whenLoaded('category'),
            'sub_category' => $this->whenLoaded('subCategory'),
            'seller'       => $this->whenLoaded('seller'),
            'pictures'     => AdsPictureResource::collection($this->pictures),
            'total_rating' => $totalRating,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at
        ];
    }
}
