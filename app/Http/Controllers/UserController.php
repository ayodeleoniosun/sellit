<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UpdatePasswordRequest;
use App\Http\Requests\Users\UpdateProfilePictureRequest;
use App\Http\Requests\Users\UpdateUserBusinessInformationRequest;
use App\Http\Requests\Users\UpdateUserProfileRequest;
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

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        return $this->request(
            'update-profile',
            $request,
            'Profile successfully updated',
            Response::HTTP_OK
        );
    }

    public function updateBusinessProfile(UpdateUserBusinessInformationRequest $request): JsonResponse
    {
        return $this->request(
            'update-business-profile',
            $request,
            'Business profile successfully updated',
            Response::HTTP_OK
        );
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        return $this->request(
            'update-password',
            $request,
            'Password successfully updated',
            Response::HTTP_OK
        );
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request): JsonResponse
    {
        return $this->request(
            'update-profile-picture',
            $request,
            'Profile picture successfully updated',
            Response::HTTP_OK
        );
    }

    public function request(string $type, $request, string $successMessage, string $httpCode): JsonResponse
    {
        try {
            switch ($type) {
                case 'profile':
                    $response = $this->user->profile($request->all());
                    break;

                case 'update-profile':
                    $response = $this->user->updateProfile($request->user(), $request->validated());
                    break;

                case 'update-business-profile':
                    $response = $this->user->updateBusinessProfile($request->user(), $request->validated());
                    break;

                case 'update-password':
                    $response = $this->user->updatePassword($request->user(), $request->validated());
                    break;

                case 'update-profile-picture':
                    $response = $this->user->updateProfilePicture($request->user(), $request->validated());
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
