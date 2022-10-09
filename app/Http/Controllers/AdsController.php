<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AdsServiceInterface;
use App\Http\Requests\Ads\CreateNewAdsRequest;
use App\Http\Requests\Ads\UploadAdsPicturesRequest;
use App\Http\Resources\Ads\AdsCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    private AdsServiceInterface $ads;

    public function __construct(AdsServiceInterface $ads)
    {
        $this->ads = $ads;
    }

    public function myAds(Request $request): AdsCollection
    {
        return $this->ads->myAds($request);
    }

    public function store(CreateNewAdsRequest $request): JsonResponse
    {
        $response = $this->ads->store($request);
        return response()->success($response, 'Ads successfully added');
    }

    public function update(CreateNewAdsRequest $request, int $adsId): JsonResponse
    {
        $response = $this->ads->update($request, $adsId);
        return response()->success($response, 'Ads successfully updated');
    }

    public function uploadPictures(UploadAdsPicturesRequest $request, int $adsId): JsonResponse
    {
        $response = $this->ads->uploadPictures($request, $adsId);
        return response()->success($response, 'Ads pictures successfully uploaded');
    }

    public function deletePicture(Request $request, int $adsId, int $pictureId): JsonResponse
    {
        $this->ads->deletePicture($request, $adsId, $pictureId);
        return response()->success([], 'Ads picture successfully deleted');
    }
}
