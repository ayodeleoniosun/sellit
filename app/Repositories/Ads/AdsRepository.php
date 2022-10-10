<?php

namespace App\Repositories\Ads;

use App\Contracts\Repositories\Ads\AdsRepositoryInterface;
use App\Contracts\Repositories\File\FileRepositoryInterface;
use App\Models\Ads;
use App\Models\AdsPicture;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdsRepository extends BaseRepository implements AdsRepositoryInterface
{
    protected Ads $ads;

    protected FileRepositoryInterface $fileRepo;

    /**
     * @param Ads $ads
     * @param FileRepositoryInterface $fileRepo
     */
    public function __construct(Ads $ads, FileRepositoryInterface $fileRepo)
    {
        parent::__construct($ads);
        $this->ads = $ads;
        $this->fileRepo = $fileRepo;
    }

    public function index(Request $request): LengthAwarePaginator
    {
        return $this->ads->with('category', 'subCategory', 'pictures')->paginate(10);
    }

    public function myAds(Request $request): LengthAwarePaginator
    {
        return $this->ads->whereSellerId($request->user()->id)->with('category', 'subCategory', 'pictures')->paginate(10);
    }

    public function categoryAds(Request $request, int $categoryId): LengthAwarePaginator
    {
        return $this->ads->whereCategoryId($categoryId)->with('seller', 'pictures')->paginate(10);
    }

    public function subCategoryAds(Request $request, int $categoryId, int $subCategoryId): LengthAwarePaginator
    {
        return $this->ads->whereCategoryId($categoryId)->whereSubCategoryId($subCategoryId)->with('seller', 'pictures')->paginate(10);
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

    public function uploadPictures(array $pictures, int $adsId): Model
    {
        $ads = $this->ads->find($adsId);

        $allPictures = [];

        foreach ($pictures as $picture) {
            $allPictures[] = $this->fileRepo->insertGetId([
                'path' => $picture,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($allPictures as $picture) {
            $ads->pictures()->create([
                'ads_id' => $adsId,
                'file_id' => $picture
            ]);
        }

        return $this->find($adsId, ['category', 'subCategory', 'pictures']);
    }

    public function deletePicture(AdsPicture $adsPicture): void
    {
        $file = $this->fileRepo->find($adsPicture->file_id);
        $file->delete();
    }

    public function getPicture(Ads $ads, int $pictureId): AdsPicture|null
    {
        return $ads->pictures()->find($pictureId);
    }
}
