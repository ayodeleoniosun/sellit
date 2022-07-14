<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\ResetPasswordRequest;
use App\Http\Requests\Users\UserRegistrationRequest;
use App\Services\Interfaces\AccountServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    private AccountServiceInterface $account;

    public function __construct(AccountServiceInterface $account)
    {
        $this->account = $account;
    }

    public function register(UserRegistrationRequest $request): JsonResponse
    {
        return $this->request(
            'register',
            $request,
            'Registration successful',
            Response::HTTP_CREATED
        );
    }

    public function login(Request $request): JsonResponse
    {
        return $this->request(
            'login',
            $request,
            'Login successful',
            Response::HTTP_OK
        );
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        return $this->request(
            'forgot-password',
            $request,
            'Reset password link successfully sent to ' . $request->email_address,
            Response::HTTP_OK
        );
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->request(
            'reset-password',
            $request,
            'Password successfully reset',
            Response::HTTP_OK
        );
    }

    public function request(string $type, $request, string $successMessage, string $httpCode): JsonResponse
    {
        try {
            switch ($type) {
                case 'register':
                    $response = $this->account->register($request->validated());
                    break;

                case 'login':
                    $response = $this->account->login($request->all());
                    break;

                case 'forgot-password':
                    $response = $this->account->forgotPassword($request->all());
                    break;

                case 'reset-password':
                    $response = $this->account->resetPassword($request->validated());
                    break;

                default:
                    $response = null;
            }

            return response()->json([
                'status'  => 'success',
                'message' => $successMessage,
                'data'    => $response,
            ], $httpCode);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        }
    }
}
