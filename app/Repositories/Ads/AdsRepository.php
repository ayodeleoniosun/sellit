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

    public function getAds(string $slug): ?Ads
    {
        $ads = $this->ads->where('slug', $slug);
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

        return $this->getAds($ads->slug);
    }
}
