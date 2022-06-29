<?php

namespace App\Services\Interfaces;

use App\Http\Requests\Users\UserRegistrationRequest;
use App\Models\User;

interface AccountServiceInterface
{
    public function register(array $data): User;

    public function login(array $data): array;
}
