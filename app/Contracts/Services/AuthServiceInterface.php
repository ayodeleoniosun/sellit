<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(array $data): Model;

    public function login(array $data): array;

    public function forgotPassword(Request $request): string;

    public function updatePassword(Request $request): string;
}
