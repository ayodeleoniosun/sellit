<?php

namespace App\Services\Interfaces;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function index(Request $request): UserCollection;

    public function profile(array $data): UserResource;

    public function updateProfile(User $user, array $data): UserResource;

    public function updatePassword(User $user, array $data): string;

    public function updateProfilePicture(User $user, array $data): UserResource;

    public function logout(User $user): int;
}
