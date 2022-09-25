<?php

namespace App\Contracts\Repositories\Ads;

use App\Models\Ads;

interface AdsRepositoryInterface
{
    public function store(array $data): Ads;

    public function update(array $data, int $adsId): Ads;

    public function getAdsBySlug(string $slug): ?Ads;

    public function getAdsById(int $adsId): ?Ads;

    public function sellerAdsExist(string $slug, int $seller, int $adsId, bool $new = true): ?Ads;
}
