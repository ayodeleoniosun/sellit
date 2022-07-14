<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $phoneNumberExist = $this->userRepo->getDuplicateUserByPhoneNumber($data['phone_number'], $user->id);

        if ($phoneNumberExist) {
            abort(403, 'Phone number belongs to another user');
        }

        return new UserResource($this->userRepo->updateProfile($data, $user));
    }

    public function updateBusinessProfile(User $user, array $data): UserResource
    {
        return new UserResource($this->userRepo->updateBusinessProfile($data, $user));
    }

    public function updatePassword(User $user, array $data): UserResource
    {
        if (!$user || !Hash::check($data['current_password'], $user->password)) {
            abort(403, 'Incorrect current password');
        }

        return new UserResource($this->userRepo->updatePassword($data, $user));
    }

    public function updateProfilePicture(User $user, array $data): UserResource
    {
        $image = (object)$data['image'];

        $extension = $image->extension();
        $filename = $user->id . '' . time() . '.' . $extension;

        Storage::disk('profile_pictures')->put($filename, file_get_contents($image->getRealPath()));

        $path = Storage::disk('profile_pictures')->url($filename);

        return new UserResource($this->userRepo->updateProfilePicture($path, $user));
    }
}
