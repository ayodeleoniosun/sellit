<?php

namespace App\Contracts\Repositories\Ads;

use App\Models\Ads;

interface AdsRepositoryInterface
{
    public function store(array $data): Ads;

    public function getAds(string $slug): ?Ads;

    public function sellerAdsExist(string $slug, int $seller, int $adsId, bool $new = true): ?Ads;
}
