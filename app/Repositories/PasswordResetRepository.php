<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    private PasswordReset $token;

    public function __construct(PasswordReset $token)
    {
        $this->token = $token;
    }

    public function create(array $data): PasswordReset
    {
        return $this->token->create($data);
    }

    public function getToken(string $token): ?PasswordReset
    {
        return $this->token->where([
            'token' => $token,
            'used'  => false,
        ])->first();
    }

    public function invalidateToken(PasswordReset $token): void
    {
        $token->used = true;
        $token->save();
    }
}
