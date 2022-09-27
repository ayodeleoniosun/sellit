<?php

namespace App\Contracts\Repositories\User;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function createToken(User $user): string;
}
