<?php

namespace App\Contracts\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function getUser(int $userId): ?Model;

    public function getUserBySlug(string $slug): ?User;

    public function getUserByEmailAddress(string $email): ?User;

    public function phoneExist(string $phone, int $id): bool;

    public function businessExist(string $name, int $userId): bool;

    public function updateProfile(array $data, User $user): Model;

    public function updateUserProfile(array $data, User $user): void;

    public function updateBusinessProfile(array $data, User $user): Model;

    public function updateProfilePicture(string $filename, User $user): Model;

    public function logout(User $user): int;
}
