<?php

namespace App\Contracts\Repositories\Ads;

use App\Models\Ads;

interface AdsRepositoryInterface
{
    public function uploadPictures(array $pictures, int $adsId);

    public function sellerAdsExist(string $slug, int $seller, int $adsId, bool $new = true): ?Ads;
}
