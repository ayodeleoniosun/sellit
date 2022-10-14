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

    public function index(Request $request): AdsCollection
    {
        return $this->ads->index($request);
    }

    public function myAds(Request $request): AdsCollection
    {
        return $this->ads->myAds($request);
    }

    public function categoryAds(Request $request, int $categoryId): AdsCollection
    {
        return $this->ads->categoryAds($request, $categoryId);
    }

    public function subCategoryAds(Request $request, int $categoryId, int $subCategoryId): AdsCollection
    {
        return $this->ads->subCategoryAds($request, $categoryId, $subCategoryId);
    }

    public function store(CreateNewAdsRequest $request): JsonResponse
    {
        $response = $this->ads->store($request);

        return response()->success($response, 'Ads successfully added', 201);
    }

    public function update(CreateNewAdsRequest $request, int $adsId): JsonResponse
    {
        $response = $this->ads->update($request, $adsId);

        return response()->success($response, 'Ads successfully updated');
    }

    public function storeSortOptions(Request $request, int $adsId): JsonResponse
    {
        $response = $this->ads->storeSortOptions($request, $adsId);

        if ($response > 0) {
            return response()->success([], $response.' sort options successfully added', 201);
        }

        return response()->error('No sort options added');
    }

    public function uploadPictures(UploadAdsPicturesRequest $request, int $adsId): JsonResponse
    {
        $response = $this->ads->uploadPictures($request, $adsId);

        return response()->success($response, 'Ads pictures successfully uploaded', 201);
    }

    public function deletePicture(Request $request, int $adsId, int $pictureId): JsonResponse
    {
        $this->ads->deletePicture($request, $adsId, $pictureId);

        return response()->success([], 'Ads picture successfully deleted', 204);
    }
}
