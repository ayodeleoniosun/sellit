<?php

namespace App\Repositories;

use App\Models\PasswordReset;
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

    public function getToken(array $data): ?PasswordReset
    {
        return $this->token->where([
            'email' => $data['email_address'],
            'token' => $data['token'],
        ])->first();
    }

    public function deleteToken(PasswordReset $token): void
    {
        $token->delete();
    }
}
