<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getUsers(): Collection;

    public function getUser(string $slug): ?User;

    public function getUserByEmailAddress(string $email): ?User;

    public function getDuplicateUserByPhoneNumber(string $phone, int $id): ?User;

    public function updateProfile(array $data, User $user): User;

    public function updateUserProfile(array $data, User $user): void;

    public function updateBusinessProfile(array $data, User $user): User;

    public function updatePassword(array $data, User $user): User;

    public function updateProfilePicture(string $path, User $user): User;

    public function logout(User $user): int;
}
