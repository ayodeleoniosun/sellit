<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\Interfaces\AccountServiceInterface;
use Illuminate\Support\Str;

class AccountService implements AccountServiceInterface
{
    protected AccountRepositoryInterface $accountRepositoryInterface;

    public function __construct(AccountRepositoryInterface $accountRepositoryInterface)
    {
        $this->accountRepositoryInterface = $accountRepositoryInterface;
    }

    public function register(array $data): User
    {
        $fullname = strtolower($data['first_name'] . ' ' . $data['last_name']);

        $data['slug'] = Str::slug($fullname) . '-' . strtolower(Str::random(8));
        $data['password'] = bcrypt($data['password']);

        return $this->accountRepositoryInterface->store($data);
    }
}
