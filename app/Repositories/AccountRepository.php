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
}
