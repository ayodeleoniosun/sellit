<?php

namespace App\Entities\Repositories\User;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function store(array $data): User;

    public function getUserByEmailAddress(string $email): ?User;
}
