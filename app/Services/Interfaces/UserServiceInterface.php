<?php

namespace App\Services\Interfaces;

use App\Http\Resources\UserResource;
use App\Models\User;

interface UserServiceInterface
{
    public function profile(array $data): UserResource;

    public function updateProfile(User $user, array $data): UserResource;

    public function updatePassword(User $user, array $data): string;

    public function updateProfilePicture(User $user, array $data): UserResource;

    public function logout(User $user): int;
}
