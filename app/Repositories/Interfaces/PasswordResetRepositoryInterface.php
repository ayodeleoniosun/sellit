<?php

namespace App\Repositories\Interfaces;

use App\Models\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function create(array $data): PasswordReset;

    public function getToken(string $token): ?PasswordReset;

    public function invalidateToken(PasswordReset $token): void;

}
