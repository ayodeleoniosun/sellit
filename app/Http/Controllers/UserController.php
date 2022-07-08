<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserServiceInterface $user;

    public function __construct(UserServiceInterface $user)
    {
        $this->user = $user;
    }

    public function profile(Request $request, string $slug): JsonResponse
    {
        $request->merge(['slug' => $slug]);

        return $this->request(
            'profile',
            $request,
            '',
            Response::HTTP_OK
        );
    }

    public function update(Request $request, string $type): JsonResponse
    {
        $request->merge(['type' => $type]);

        return $this->request(
            'update',
            $request,
            'Profile successfully updated',
            Response::HTTP_OK
        );
    }

    public function request(string $type, $request, string $successMessage, string $httpCode): JsonResponse
    {
        try {
            $response = null;

            if ($type === 'profile') {
                $response = $this->user->profile($request->all());
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
