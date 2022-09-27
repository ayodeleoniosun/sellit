<?php

namespace App\Repositories\User;

use App\Contracts\Repositories\User\AuthRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function createToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
