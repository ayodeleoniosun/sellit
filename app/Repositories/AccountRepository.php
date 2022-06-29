<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function store(array $data): User
    {
        return $this->user->create($data);
    }

    public function getUserByEmailAddress(string $email): User
    {
        return $this->user->where('email_address', $email)->first();
    }

    public function createToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
