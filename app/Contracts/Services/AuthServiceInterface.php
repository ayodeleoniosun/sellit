<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(array $data): User;

    public function login(array $data): array;

    public function forgotPassword(Request $request): string;

    public function updatePassword(Request $request): string;
}
