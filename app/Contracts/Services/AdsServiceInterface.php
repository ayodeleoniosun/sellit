<?php

namespace App\Contracts\Services;

use App\Http\Requests\Ads\CreateNewAdsRequest;
use App\Http\Resources\Ads\AdsResource;

interface AdsServiceInterface
{
    public function store(CreateNewAdsRequest $request): AdsResource;

    public function update(CreateNewAdsRequest $request, int $adsId): AdsResource;
}
