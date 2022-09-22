<?php

namespace App\Http\Controllers;

use App\Contracts\Services\UserServiceInterface;
use App\Http\Requests\Users\UpdatePasswordRequest;
use App\Http\Requests\Users\UpdateProfilePictureRequest;
use App\Http\Requests\Users\UpdateUserBusinessInformationRequest;
use App\Http\Requests\Users\UpdateUserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserServiceInterface $user;

    public function __construct(UserServiceInterface $user)
    {
        $this->user = $user;
    }

    public function index(Request $request): JsonResponse
    {
        $response = $this->user->index($request);
        return response()->success($response);
    }

    public function profile(Request $request, string $slug): JsonResponse
    {
        $request->merge(['slug' => $slug]);
        $response = $this->user->profile($request->all());

        return response()->success($response);
    }

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        $response = $this->user->updateProfile($request->user(), $request->validated());
        return response()->success($response, 'Profile successfully updated');
    }

    public function updateBusinessProfile(UpdateUserBusinessInformationRequest $request): JsonResponse
    {
        $response = $this->user->updateBusinessProfile($request->user(), $request->validated());
        return response()->success($response, 'Business profile successfully updated');
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $response = $this->user->updatePassword($request->user(), $request->validated());
        return response()->success([], 'Password successfully updated');
    }

    public function updateProfilePicture(UpdateProfilePictureRequest $request): JsonResponse
    {
        $response = $this->user->updateProfilePicture($request->user(), $request->validated());
        return response()->success($response, 'Profile picture successfully updated');
    }

    public function logout(Request $request): JsonResponse
    {
        $this->user->logout($request->user());
        return response()->success([], 'User logged out');
    }
}
