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
        return $this->filterAds($request);
    }

    public function myAds(Request $request): LengthAwarePaginator
    {
        return $this->filterAds($request, 'user');
    }

    public function categoryAds(Request $request, int $categoryId): LengthAwarePaginator
    {
        return $this->filterAds($request, 'category', $categoryId);
    }

    public function subCategoryAds(Request $request, int $categoryId, int $subCategoryId): LengthAwarePaginator
    {
        return $this->filterAds($request, 'category', $categoryId, $subCategoryId);
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

    public function filterAds(Request $request, string|null $type = null, int|null $categoryId = null,  int|null $subCategoryId = null): LengthAwarePaginator
    {

        if ($type === 'user') {
            $ads = $this->ads->whereSellerId($request->user()->id)->with('category', 'subCategory', 'pictures');
        } else if ($type === 'category') {
            $ads = $this->ads->whereCategoryId($categoryId)->with('seller', 'pictures');
        }  else if ($type === 'sub_category') {
            $ads = $this->ads->whereCategoryId($categoryId)->whereSubCategoryId($subCategoryId)->with('seller', 'pictures');
        } else {
            $ads = $this->ads->with('category', 'subCategory', 'seller', 'pictures');
        }

        if ($request->type === 'newest') {
            $ads = $ads->latest();
        }

        if ($request->type === 'oldest') {
            $ads = $ads->oldest();
        }

        if ($request->price === 'lowest') {
            $ads = $ads->orderBy('price');
        }

        if ($request->price === 'highest') {
            $ads = $ads->orderBy('price', 'DESC');
        }

        return $ads->paginate(10);
    }
}
