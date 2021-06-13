<?php

namespace App\Modules\Api\V1\Services;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\Ads;
use App\Modules\Api\V1\Models\User;
use App\Modules\Api\V1\Repositories\AdminRepository;
use App\Modules\Api\V1\Resources\AdsResource;
use App\Modules\Api\V1\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class AdminService implements AdminRepository
{
    public function overview()
    {
        $total_ads = Ads::count();
        $total_active_ads = Ads::where('active_status', ActiveStatus::ACTIVE)->count();
        $total_users = User::count();
        $total_active_users = User::where('active_status', ActiveStatus::ACTIVE)->count();
        $latest_ads = Ads::where('active_status', ActiveStatus::ACTIVE)->latest()->take(10)->get();
        $latest_users = User::where('active_status', ActiveStatus::ACTIVE)->latest()->take(10)->get();
        $user_with_highest_ads = User::withCount('ads')->orderBy('ads_count', 'DESC')->take(1)->first();
        
        return [
            'total_ads' => $total_ads,
            'total_active_ads' => $total_active_ads,
            'total_users' => $total_users,
            'total_active_users' => $total_active_users,
            'user_with_highest_ads' => $user_with_highest_ads,
            'latest_ads' => AdsResource::collection($latest_ads),
            'latest_users' => UserResource::collection($latest_users)
        ];
    }
}
