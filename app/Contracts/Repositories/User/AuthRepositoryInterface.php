<?php

namespace App\Contracts\Repositories\User;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function store(array $data): User;

    public function getUserByEmailAddress(string $email): ?User;
}
