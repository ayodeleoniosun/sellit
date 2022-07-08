<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function profile(array $data): UserResource
    {
        $user = $this->userRepo->getUser($data['slug']);

        if (!$user) {
            abort(404, 'User not found');
        }

        return new UserResource($user);
    }

    public function updateProfile(User $user, array $data): UserResource
    {
        $type = $data['type'];

        if ($type === 'personal-information') {
            $phoneNumberExist = $this->userRepo->getDuplicateUserByPhoneNumber($data['phone_number'], $user->id);

            if ($phoneNumberExist) {
                abort(403, 'Phone number belongs to another user');
            }
            return new UserResource($this->userRepo->updateProfile($data, $user));
        } else if ($type === 'business-information') {
            return new UserResource($this->userRepo->updateBusinessProfile($data, $user));
        }
    }
}
