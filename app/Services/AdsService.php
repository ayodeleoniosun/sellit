<?php

namespace App\Services;

use App\Contracts\Repositories\Ads\AdsRepositoryInterface;
use App\Contracts\Services\AdsServiceInterface;
use App\Exceptions\Ads\AdsExistException;
use App\Exceptions\CustomException;
use App\Http\Requests\Ads\CreateNewAdsRequest;
use App\Http\Resources\Ads\AdsResource;
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

        return new AdsResource($this->adsRepo->store($data));
    }

    /**
     * @throws CustomException
     */
    public function update(CreateNewAdsRequest $request, int $adsId): AdsResource
    {
        $data = $request->validated();
        $sellerId = $request->user()->id;
        $data['slug'] = Str::kebab($data['name']);

        $sellerAdsExist = $this->adsRepo->sellerAdsExist($data['slug'], $sellerId, $adsId, false);

        if ($sellerAdsExist) {
            throw new CustomException('You have added this ads before.');
        }

        return new AdsResource($this->adsRepo->update($data, $adsId));
    }
}
