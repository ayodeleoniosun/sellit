<?php

namespace App\Services\Interfaces;

use App\Http\Resources\UserResource;
use App\Models\User;

interface UserServiceInterface
{
    public function profile(array $data): UserResource;

    public function updateProfile(User $user, array $data): UserResource;
}
