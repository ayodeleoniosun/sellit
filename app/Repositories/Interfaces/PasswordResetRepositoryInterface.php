<?php

namespace App\Repositories\Interfaces;

use App\Models\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function create(array $data): PasswordReset;

    public function getToken(array $data): ?PasswordReset;

    public function deleteToken(PasswordReset $token): void;

}
