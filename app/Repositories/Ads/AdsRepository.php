<?php

namespace App\Repositories\Ads;

use App\Contracts\Repositories\Ads\AdsRepositoryInterface;
use App\Models\Ads;

class AdsRepository implements AdsRepositoryInterface
{
    private Ads $ads;

    public function __construct(Ads $ads)
    {
        $this->ads = $ads;
    }

    public function getAdsBySlug(string $slug): ?Ads
    {
       $ads = $this->ads->where('slug', $slug);
       return $ads->with('category', 'subCategory', 'sortOptions', 'seller', 'pictures')->first() ?? null;
    }

    public function getAdsById(int $adsId): ?Ads
    {
        $ads = $this->ads->find($adsId);

        return $ads->with('category', 'subCategory', 'sortOptions', 'seller', 'pictures')->first() ?? null;
    }

    public function sellerAdsExist(string $slug, int $seller, ?int $adsId, bool $new = true): ?Ads
    {
        if ($new) {
            return $this->ads->where([
                'slug' => $slug,
                'seller_id' => $seller
            ])->first();
        }

        return $this->ads->where([
            'slug' => $slug,
            'seller_id' => $seller
        ])->where('id', '<>', $adsId)->first();
    }

    public function store(array $data): Ads
    {
        $ads = $this->ads->create($data);
        return $this->getAdsBySlug($ads->slug);
    }

    public function update(array $data, int $adsId): Ads
    {
        $ads = $this->ads->find($adsId);
        $ads->update($data);
        $ads->refresh();
        return $this->getAdsBySlug($ads->slug);
    }
}
