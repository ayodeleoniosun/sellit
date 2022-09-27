<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AdsServiceInterface;
use App\Http\Requests\Ads\CreateNewAdsRequest;
use App\Http\Requests\Ads\UploadAdsPicturesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    private AdsServiceInterface $ads;

    public function __construct(AdsServiceInterface $ads)
    {
        $this->ads = $ads;
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
        $response = $this->ads->uploadPictures($request->pictures, $adsId);

        if ($response > 0) {
            return response()->success([], $response.' ads pictures successfully uploaded');
        }

        return response()->error('No ads picture uploaded');
    }
}
