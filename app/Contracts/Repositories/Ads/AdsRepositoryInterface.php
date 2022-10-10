<?php

namespace App\Contracts\Repositories\Ads;

use App\Models\Ads;
use App\Models\AdsPicture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface AdsRepositoryInterface
{
    public function myAds(Request $request): LengthAwarePaginator;

    public function uploadPictures(array $pictures, int $adsId): Model;

    public function deletePicture(AdsPicture $adsPicture): void;

    public function sellerAdsExist(string $slug, int $seller, int $adsId, bool $new = true): Ads|null;

    public function getPicture(Ads $ads, int $pictureId): AdsPicture|null;
}