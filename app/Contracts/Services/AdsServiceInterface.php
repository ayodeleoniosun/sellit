<?php

namespace App\Contracts\Services;

use App\Http\Requests\Ads\CreateNewAdsRequest;
use App\Http\Requests\Ads\UploadAdsPicturesRequest;
use App\Http\Resources\Ads\AdsCollection;
use App\Http\Resources\Ads\AdsResource;
use Illuminate\Http\Request;

interface AdsServiceInterface
{
    public function index(Request $request): AdsCollection;

    public function myAds(Request $request): AdsCollection;

    public function categoryAds(Request $request, int $categoryId): AdsCollection;

    public function subCategoryAds(Request $request, int $categoryId, int $subCategoryId): AdsCollection;

    public function store(CreateNewAdsRequest $request): AdsResource;

    public function update(CreateNewAdsRequest $request, int $adsId): AdsResource;

    public function uploadPictures(UploadAdsPicturesRequest $request, int $adsId): AdsResource;

    public function deletePicture(Request $request, int $adsId, int $pictureId): void;

    public function storeSortOptions(Request $request, int $adsId): int;

}
