<?php

namespace App\Contracts\Repositories\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getUsers(Request $request): Collection;

    public function getUser(string $slug): ?User;

    public function getUserByEmailAddress(string $email): ?User;

    public function getDuplicateUserByPhoneNumber(string $phone, int $id): ?User;

    public function updateProfile(array $data, User $user): User;

    public function updateUserProfile(array $data, User $user): void;

    public function updateBusinessProfile(array $data, User $user): User;

    public function updatePassword(array $data, User $user): User;

    public function updateProfilePicture(string $filename, User $user): User;

    public function logout(User $user): int;
}
