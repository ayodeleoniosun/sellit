<?php

namespace App\Services;

use App\Contracts\Repositories\Ads\AdsRepositoryInterface;
use App\Contracts\Services\AdsServiceInterface;
use App\Exceptions\CustomException;
use App\Http\Requests\Ads\CreateNewAdsRequest;
use App\Http\Requests\Ads\UploadAdsPicturesRequest;
use App\Http\Resources\Ads\AdsCollection;
use App\Http\Resources\Ads\AdsResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdsService implements AdsServiceInterface
{
    protected AdsRepositoryInterface $adsRepo;

    /**
     * @param AdsRepositoryInterface $adsRepo
     */
    public function __construct(AdsRepositoryInterface $adsRepo)
    {
        $this->adsRepo = $adsRepo;
    }

    public function index(Request $request): AdsCollection
    {
        return new AdsCollection($this->adsRepo->index($request));
    }

    public function view(int $adsId): AdsResource
    {
        return new AdsResource($this->adsRepo->find($adsId, ['seller', 'category', 'subCategory', 'allSortOptions']));
    }

    public function myAds(Request $request): AdsCollection
    {
        return new AdsCollection($this->adsRepo->myAds($request));
    }

    public function categoryAds(Request $request, int $categoryId): AdsCollection
    {
        return new AdsCollection($this->adsRepo->categoryAds($request, $categoryId));
    }

    public function subCategoryAds(Request $request, int $categoryId, int $subCategoryId): AdsCollection
    {
        return new AdsCollection($this->adsRepo->subCategoryAds($request, $categoryId, $subCategoryId));
    }

    /**
     * @throws CustomException
     */
    public function store(CreateNewAdsRequest $request): AdsResource
    {
        $data = $request->validated();
        $data['seller_id'] = $request->user()->id;
        $data['slug'] = Str::kebab($data['name']);

        $sellerAdsExist = $this->adsRepo->sellerAdsExist($data['slug'], $data['seller_id'], null);

        if ($sellerAdsExist) {
            throw new CustomException('You have added this ads before.');
        }

        return new AdsResource($this->adsRepo->create($data));
    }

    /**
     * @throws CustomException
     */
    public function update(CreateNewAdsRequest $request, int $adsId): AdsResource
    {
        $sellerId = $request->user()->id;

        $this->validateAds($sellerId, $adsId);

        $data = $request->validated();

        $data['slug'] = Str::kebab($data['name']);

        $sellerAdsExist = $this->adsRepo->sellerAdsExist($data['slug'], $sellerId, $adsId, false);

        if ($sellerAdsExist) {
            throw new CustomException('You have added this ads before.');
        }

        $relationsToRetrieve = ['category', 'subCategory', 'allSortOptions', 'seller', 'pictures'];

        return new AdsResource($this->adsRepo->update($adsId, $data, $relationsToRetrieve));
    }

    /**
     * @throws CustomException
     */
    public function uploadPictures(UploadAdsPicturesRequest $request, int $adsId): AdsResource
    {
        $this->validateAds($request->user()->id, $adsId);
        $paths = [];

        foreach ($request->pictures as $key => $picture) {
            $picture = (object) $picture;
            $filename = $adsId . $key . time() . '.' . $picture->extension();

            Storage::disk('s3')->put($filename, file_get_contents($picture->getRealPath()));
            $paths[] = $filename;
        }

        return new AdsResource($this->adsRepo->uploadPictures($paths, $adsId));
    }

    /**
     * @throws CustomException
     */
    public function deletePicture(Request $request, int $adsId, int $pictureId):void
    {
        $ads = $this->validateAds($request->user()->id, $adsId);

        $adsPicture = $this->adsRepo->getPicture($ads, $pictureId);

        if (!$adsPicture) {
            throw new CustomException('Invalid resource');
        }

        Storage::disk('s3')->delete($adsPicture->file->path);

        $this->adsRepo->deletePicture($adsPicture);
    }

    /**
     * @throws CustomException
     */
    public function storeSortOptions(Request $request, int $adsId): int|string
    {
        $ads = $this->validateAds($request->user()->id, $adsId);

        return $this->adsRepo->storeSortOptionValues($request->sort_option_values, $ads);
    }

    /**
     * @throws CustomException
     */
    private function validateAds(int $userId, int $adsId): Model
    {
        $ads = $this->adsRepo->find($adsId);

        if (!$ads) {
            throw new CustomException('Ads does not exist');
        }

        if ($ads->seller_id !== $userId) {
            throw new CustomException('Unauthorized', 403);
        }

        return $ads;
    }
}
