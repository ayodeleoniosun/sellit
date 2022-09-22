<?php

namespace App\Http\Resources\SubCategory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubCategoryCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return ['sub_categories' => $this->collection];
    }
}
