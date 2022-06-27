<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserRegistrationRequest;
use App\Services\Interfaces\AccountServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    private AccountServiceInterface $account;

    public function __construct(AccountServiceInterface $account)
    {
        $this->account = $account;
    }

    public function register(UserRegistrationRequest $request)
    {
        return $this->request(
            'register',
            $request,
            'Registration successful',
            Response::HTTP_CREATED
        );
    }

    public function request(string $type, $request, string $successMessage, string $httpCode): JsonResponse
    {
        try {
            $response = null;

            if ($type === 'register') {
                $response = $this->account->register($request->validated());
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
