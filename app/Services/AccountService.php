<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Jobs\SendForgotPasswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use App\Services\Interfaces\AccountServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountService implements AccountServiceInterface
{
    protected AccountRepositoryInterface $accountRepo;
    protected PasswordResetRepositoryInterface $passwordResetRepo;

    public function __construct(
        AccountRepositoryInterface       $accountRepo,
        PasswordResetRepositoryInterface $passwordResetRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->passwordResetRepo = $passwordResetRepo;
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

    public function forgotPassword(array $data): ?PasswordReset
    {
        $user = $this->accountRepo->getUserByEmailAddress($data['email_address']);

        if (!$user) {
            abort(404, 'Email address does not exist');
        }

        $token = Str::random(60);
        $forgotPasswordLink = config('app.url') . '/reset-password?token=' . $token;
        $expiration = Carbon::now()->addMinutes(10)->toDateTimeString();

        SendForgotPasswordMail::dispatch($user, $forgotPasswordLink);

        return $this->passwordResetRepo->create([
            'email'      => $user->email_address,
            'token'      => $token,
            'expires_at' => $expiration,
        ]);
    }

    public function resetPassword(array $data): User
    {
        $token = $this->passwordResetRepo->getToken($data);

        if (!$token) {
            abort(403, 'Invalid token');
        }

        $user = $this->accountRepo->getUserByEmailAddress($token->email);

        $tokenExpiryMinutes = $token->created_at->diffInMinutes(now());
        $configExpiryMinutes = config('auth.passwords.users.expire');

        if ($tokenExpiryMinutes > $configExpiryMinutes) {
            abort(403, 'Token has expired. Kindly request for a forgot password link again.');
        }

        DB::transaction(function () use ($data, $user, $token) {
            $this->accountRepo->updatePassword($data, $user);
            $this->passwordResetRepo->deleteToken($token);
        });

        $user->refresh();

        return $user;
    }
}
