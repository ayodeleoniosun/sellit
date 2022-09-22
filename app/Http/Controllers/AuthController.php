<?php

namespace App\Http\Controllers;

use App\Entities\Services\AuthServiceInterface;
use App\Http\Requests\Users\UserRegistrationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthServiceInterface $auth;

    public function __construct(AuthServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $response = $this->auth->register($request->validated());
        return response()->success($response, 'Registration successful.', 201);
    }

    public function login(Request $request): JsonResponse
    {
        $response = $this->auth->login($request->all());
        return response()->success($response, 'Login successful.');
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $response = $this->auth->forgotPassword($request);
        return response()->success([], $response);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $response = $this->auth->updatePassword($request);
        return response()->success([], $response);
    }
}
