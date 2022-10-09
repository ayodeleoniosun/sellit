<?php

namespace App\Http\Resources\Ads;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdsCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
