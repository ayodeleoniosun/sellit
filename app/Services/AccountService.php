<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\Interfaces\AccountServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService implements AccountServiceInterface
{
    protected AccountRepositoryInterface $accountRepo;

    public function __construct(AccountRepositoryInterface $accountRepo)
    {
        $this->accountRepo = $accountRepo;
    }

    public function register(array $data): User
    {
        $fullname = strtolower($data['first_name'] . ' ' . $data['last_name']);

        $data['slug'] = Str::slug($fullname) . '-' . strtolower(Str::random(8));
        $data['password'] = bcrypt($data['password']);

        return $this->accountRepo->store($data);
    }

    public function login(array $data): array
    {
        $user = $this->accountRepo->getUserByEmailAddress($data['email_address']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            abort(401, 'Incorrect login credentials');
        }

        $token = $this->accountRepo->createToken($user);

        return [
            'user'  => new UserResource($user),
            'token' => $token,
        ];
    }
}
