<?php
//
//namespace App\Services;
//
//use App\ApiUtility;
//use App\Exceptions\CustomApiErrorResponseHandler;
//use App\Http\Resources\AdsResource;
//use App\Models\ActiveStatus;
//use App\Models\Ads;
//use App\Models\AdsPicture;
//use App\Models\AdsSortOption;
//use App\Models\Category;
//use App\Models\File;
//use App\Models\Review;
//use App\Models\SortOption;
//use App\Models\SubCategory;
//use App\Models\SubCategorySortOption;
//use App\Repositories\AdsRepository;
//use Illuminate\Support\Arr;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Str;
//use function App\Modules\Api\V1\Services\count;
//
//class AdsService implements AdsRepository
//{
//    public function index(array $request)
//    {
//        $ads = Ads::where('active_status', ActiveStatus::ACTIVE);
//
//        if (Arr::exists($request, 'search')) {
//            $search = $request['search'];
//            $category = $request['category'] ?? null;
//
//            $ads = $ads->where(function ($query) use ($search, $category) {
//                if ($category) {
//                    $category_id = Category::where([
//                        'slug' => $category,
//                        'active_status' => ActiveStatus::ACTIVE
//                    ])->value('id');
//
//                    $query->where('category_id', $category_id)
//                        ->where(function ($sub_query) use ($search) {
//                            $sub_query->where('name', 'LIKE', '%' . $search . '%')
//                            ->orWhere('description', 'LIKE', '%' . $search . '%');
//                        });
//                } else {
//                    $query->where('name', 'LIKE', '%' . $search . '%')
//                        ->orWhere('description', 'LIKE', '%' . $search . '%');
//                }
//            })->latest();
//        } elseif (Arr::exists($request, 'filter')) {
//            $filter = $request['filter'];
//
//            if ($filter === 'category') {
//                $category = $request['category'];
//                $category_id = Category::where([
//                    'slug' => $category,
//                    'active_status' => ActiveStatus::ACTIVE
//                ])->value('id');
//
//                $ads = $ads->where(function ($query) use ($category_id) {
//                    $query->where('category_id', $category_id);
//                })->latest();
//            } elseif ($filter === 'sub_category') {
//                $sub_category = $request['sub_category'];
//                $sub_category_id = SubCategory::where([
//                    'slug' => $sub_category,
//                    'active_status' => ActiveStatus::ACTIVE
//                ])->value('id');
//
//                $ads = $ads->where(function ($query) use ($sub_category_id) {
//                    $query->where('sub_category_id', $sub_category_id);
//                })->latest();
//            } elseif ($filter === 'price') {
//                $minimum_price = (int) $request['minimum_price'];
//                $maximum_price = (int) $request['maximum_price'];
//
//                $ads = $ads->where(function ($query) use ($minimum_price, $maximum_price) {
//                    $query->whereBetween('price', [$minimum_price, $maximum_price]);
//                })->orderBy('price');
//            } elseif ($filter === 'order') {
//                $order = $request['order'];
//                $ads = ($order === 'latest') ? $ads->latest() :  $ads->oldest();
//            } elseif ($filter === 'seller') {
//                $seller = $request['seller'];
//
//                $ads = $ads->where(function ($query) use ($seller) {
//                    $query->whereHas('seller', function ($query) use ($seller) {
//                        return $query->where('user.business_slug_url', $seller);
//                    });
//                })->latest();
//            }
//        } else {
//            $ads = $ads->latest();
//        }
//
//        return AdsResource::collection($ads->get());
//    }
//
//    public function myAds(object $request)
//    {
//        $user_id = $request['auth_user']->id;
//
//        $ads = Ads::where([
//                'seller_id' => $user_id,
//                'active_status' => ActiveStatus::ACTIVE
//            ]);
//
//        if ($request->filled('search')) {
//            $search = $request->get('search');
//            $category_id = $request->get('category_id');
//
//            $ads = $ads->where(function ($query) use ($search, $category_id) {
//                if ($category_id) {
//                    $query->where('category_id', $category_id)
//                        ->where(function ($sub_query) use ($search) {
//                            $sub_query->where('name', 'LIKE', '%' . $search . '%')
//                            ->orWhere('description', 'LIKE', '%' . $search . '%');
//                        });
//                } else {
//                    $query->where('name', 'LIKE', '%' . $search . '%')
//                        ->orWhere('description', 'LIKE', '%' . $search . '%');
//                }
//            })->latest();
//        } elseif ($request->filled('filter')) {
//            $filter = $request->get('filter');
//
//            if ($filter === 'order') {
//                $order = $request->get('order');
//                $ads = ($order === 'latest') ? $ads->latest() :  $ads->oldest();
//            }
//        } else {
//            $ads = $ads->latest();
//        }
//
//        return AdsResource::collection($ads->get());
//    }
//
//    public function post(array $data)
//    {
//        $seller_id = $data['auth_user']->id;
//
//        $ads = Ads::where([
//            'name' => $data['name'],
//            'seller_id' => $seller_id,
//            'category_id' => $data['category_id'],
//            'sub_category_id' => $data['sub_category_id'],
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if ($ads) {
//            throw new CustomApiErrorResponseHandler("You have posted this ads before. Try using a different name");
//        }
//
//        $ads = new Ads();
//        $ads->category_id = $data['category_id'];
//        $ads->sub_category_id = $data['sub_category_id'];
//        $ads->seller_id = $seller_id;
//        $ads->name = $data['name'];
//        $ads->slug = strtolower(Str::snake($data['name']."_".$seller_id));
//        $ads->description = $data['description'];
//        $ads->price = $data['price'];
//        $ads->save();
//
//        return [
//            'ads' => $ads,
//            'message' => 'Ads successfully added.'
//        ];
//    }
//
//    public function postReviews(int $id, array $data)
//    {
//        $ads = Ads::where(['id' => $id, 'active_status' => ActiveStatus::ACTIVE])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads does not exist.");
//        }
//
//        if (!$data['auth_user']->id) {
//            throw new CustomApiErrorResponseHandler("Kindly login to review this ads.");
//        }
//
//        if ($ads->seller_id == $data['auth_user']->id) {
//            throw new CustomApiErrorResponseHandler("Oops, you are not allowed to review your ads.");
//        }
//
//        $review = Review::where([
//            'ads_id' => $id,
//            'buyer_id' => $data['auth_user']->id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if ($review) {
//            throw new CustomApiErrorResponseHandler("Oops, you cannot review this ads again.");
//        }
//
//        $reviews = new Review();
//        $reviews->buyer_id = $data['auth_user']->id;
//        $reviews->ads_id = $id;
//        $reviews->rating = $data['rating'];
//        $reviews->comment = $data['comment'];
//        $reviews->save();
//
//        return [
//            'reviews' => $reviews,
//            'message' => 'Reviews successfully posted.'
//        ];
//    }
//
//    public function update(int $id, array $data)
//    {
//        $ads = Ads::where(['id' => $id, 'active_status' => ActiveStatus::ACTIVE])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads does not exist.");
//        }
//
//        $seller_id = $data['auth_user']->id;
//
//        if ($ads->seller_id != $seller_id) {
//            throw new CustomApiErrorResponseHandler("You are not authorized to update this ads.");
//        }
//
//        $ads_exists = Ads::where([
//            'name' => $data['name'],
//            'seller_id' => $seller_id,
//            'category_id' => $data['category_id'],
//            'sub_category_id' => $data['sub_category_id'],
//            'active_status' => ActiveStatus::ACTIVE
//        ])->where('id', '<>', $id)->exists();
//
//        if ($ads_exists) {
//            throw new CustomApiErrorResponseHandler("You have posted this ads before. Try using a different name");
//        }
//
//
//        $ads = Ads::find($id);
//        $ads->category_id = $data['category_id'];
//        $ads->sub_category_id = $data['sub_category_id'];
//        $ads->name = $data['name'];
//        $ads->slug = strtolower(Str::snake($data['name']."_".$ads->seller_id));
//        $ads->description = $data['description'];
//        $ads->price = $data['price'];
//        $ads->save();
//
//        return [
//            'ads' => $ads,
//            'message' => 'Ads successfully updated.'
//        ];
//    }
//
//    public function addSortOptions(int $id, array $data)
//    {
//        $ads = Ads::where([
//            'id' => $id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        $all_sort_options = $data['sort_options'];
//        $added_sort_options = [];
//
//        if (count($all_sort_options) > 0) {
//            foreach ($all_sort_options as $sort_options) {
//                foreach ($sort_options as $sort_option => $value) {
//                    $sort_option_exists = SubCategorySortOption::where([
//                        'sub_category_id' => $ads->sub_category_id,
//                        'sort_option_id' => $sort_option,
//                        'active_status' => ActiveStatus::ACTIVE
//                    ])->first();
//
//                    $ads_sort_option_exists = AdsSortOption::where([
//                        'ads_id' => $ads->id,
//                        'sort_option_id' => $sort_option,
//                        'active_status' => ActiveStatus::ACTIVE
//                    ])->exists();
//
//                    if ($sort_option_exists && !$ads_sort_option_exists) {
//                        $sort_option_name = SortOption::where([
//                            'id' => $sort_option,
//                            'active_status' => ActiveStatus::ACTIVE
//                        ])->value('name');
//
//                        $value_exists = DB::table($sort_option_name)->where([
//                            'id' => $value,
//                            'active_status' => ActiveStatus::ACTIVE
//                        ])->exists();
//
//                        if ($value_exists) {
//                            AdsSortOption::create([
//                                'ads_id' => $ads->id,
//                                'sort_option_id' => $sort_option,
//                                'value' => $value
//                            ]);
//
//                            $added_sort_options[] = str_replace("_", " ", ucwords($sort_option_name));
//                        }
//                    }
//                }
//            }
//
//            if (count($added_sort_options) > 0) {
//                return [
//                    'message' => count($added_sort_options).' sort option(s) successfully added to ads: '.$ads->name,
//                    'added_sort_options' => implode(", ", $added_sort_options)
//                ];
//            }
//
//            throw new CustomApiErrorResponseHandler("No sort option added to ads: ".$ads->name);
//        }
//
//        throw new CustomApiErrorResponseHandler("No sort option added to ads: ".$ads->name);
//    }
//
//    public function view(string $slug)
//    {
//        $ads = Ads::where([
//            'slug' => $slug,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads not found.");
//        }
//
//        return new AdsResource($ads);
//    }
//
//    public function uploadPictures(array $data)
//    {
//        $user_id = $data['auth_user']->id;
//
//        $ads = Ads::where([
//            'id' => $data['ads_id'],
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads not found.");
//        }
//
//        if ($ads->seller_id != $user_id) {
//            throw new CustomApiErrorResponseHandler("You are not authorized to upload pictures to this ads.");
//        }
//
//        $pictures = $data['pictures'];
//        $uploaded = 0;
//
//        foreach ($pictures as $picture) {
//            $size = ceil($picture->getSize()/1024);
//
//            if ($size <= File::MAX_FILESIZE) {
//                $timestamp = ApiUtility::generateTimeStamp();
//                $filename = "{$timestamp}_{$ads->name}";
//                $filename = Str::slug($filename, "_");
//                $uploaded_picture = "{$filename}.{$picture->clientExtension()}";
//
//                Storage::disk('ads')->put($uploaded_picture, file_get_contents($picture->getRealPath()));
//
//                DB::beginTransaction();
//                $file = new File();
//                $file->filename = $uploaded_picture;
//                $file->type = File::ADS_FILE_TYPE;
//                $file->save();
//
//                $ads_picture = new AdsPicture();
//                $ads_picture->ads_id = $ads->id;
//                $ads_picture->file_id = $file->id;
//                $ads_picture->save();
//
//                DB::commit();
//
//                $uploaded++;
//            }
//        }
//
//        if ($uploaded > 0) {
//            return $uploaded.' ads picture(s) successfully uploaded';
//        } else {
//            throw new CustomApiErrorResponseHandler("No picture was uploaded.");
//        }
//    }
//
//    public function delete(int $ads_id, array $data)
//    {
//        $user_id = $data['auth_user']->id;
//
//        $ads = Ads::where([
//            'id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads not found.");
//        }
//
//        if ($ads->seller_id != $user_id) {
//            throw new CustomApiErrorResponseHandler("You are not authorized to delete picture from this ads.");
//        }
//
//        $ads_picture = AdsPicture::where([
//            'ads_id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->get();
//
//        DB::beginTransaction();
//
//        if ($ads_picture->count() > 0) {
//            foreach ($ads_picture as $picture) {
//                File::where([
//                    'id' => $picture->file_id,
//                    'active_status' => ActiveStatus::ACTIVE
//                ])->update(['active_status' => ActiveStatus::DELETED]);
//            }
//        }
//
//        AdsPicture::query()
//        ->where(['ads_id' => $ads_id, 'active_status' => ActiveStatus::ACTIVE])
//        ->update(['active_status' => ActiveStatus::DELETED]);
//
//        $ads->active_status = ActiveStatus::DELETED;
//        $ads->save();
//
//        DB::commit();
//
//        return 'Ads successfully deleted';
//    }
//
//    public function deletePicture(int $ads_id, int $picture_id, array $data)
//    {
//        $user_id = $data['auth_user']->id;
//
//        $ads = Ads::where([
//            'id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads not found.");
//        }
//
//        if ($ads->seller_id != $user_id) {
//            throw new CustomApiErrorResponseHandler("You are not authorized to delete picture from this ads.");
//        }
//
//        $ads_picture = AdsPicture::where([
//            'id' => $picture_id,
//            'ads_id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if (!$ads_picture) {
//            throw new CustomApiErrorResponseHandler("Ads picture does not exist");
//        }
//
//        DB::beginTransaction();
//
//        File::where([
//            'id' => $ads_picture->file_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->update(['active_status' => ActiveStatus::DELETED]);
//
//        AdsPicture::where([
//            'id' => $picture_id,
//            'ads_id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->update(['active_status' => ActiveStatus::DELETED]);
//
//        DB::commit();
//
//        return 'Ads picture successfully deleted';
//    }
//
//    public function deleteSortOption(int $ads_id, int $sort_option_id, array $data)
//    {
//        $user_id = $data['auth_user']->id;
//
//        $ads = Ads::where([
//            'id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->first();
//
//        if (!$ads) {
//            throw new CustomApiErrorResponseHandler("Ads not found.");
//        }
//
//        if ($ads->seller_id != $user_id) {
//            throw new CustomApiErrorResponseHandler("You are not authorized to delete this record.");
//        }
//
//        $delete_sort_option = AdsSortOption::where([
//            'id' => $sort_option_id,
//            'ads_id' => $ads_id,
//            'active_status' => ActiveStatus::ACTIVE
//        ])->update(['active_status' => ActiveStatus::DELETED]);
//
//        if (!$delete_sort_option) {
//            throw new CustomApiErrorResponseHandler("Ads sort option not deleted. Try again later.");
//        }
//
//        return 'Ads sort option successfully deleted';
//    }
//}
