<?php

namespace App\Contracts\Services;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface UserServiceInterface
{
    public function index(Request $request): UserCollection;

    public function profile(array $data): UserResource;

    public function updateProfile(User $user, array $data): UserResource;

    public function updatePassword(int $userId, string $newPassword): string;

    public function updateProfilePicture(User $user, UploadedFile $picture): UserResource;

    public function logout(User $user): int;
}
