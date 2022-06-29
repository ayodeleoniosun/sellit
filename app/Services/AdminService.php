<?php

namespace App\Services;

use App\Http\Resources\AdsResource;
use App\Http\Resources\UserResource;
use App\Models\ActiveStatus;
use App\Models\Ads;
use App\Models\User;
use App\Repositories\AdminRepository;

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
