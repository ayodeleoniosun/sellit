<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(Request $request): UserCollection
    {
        $users = $this->userRepo->getUsers($request);
        return new UserCollection($users);
    }

    public function profile(array $data): UserResource
    {
        $user = $this->userRepo->getUser($data['slug']);

        if (!$user) {
            throw new CustomException('User not found', 404);
        }

        return new UserResource($user);
    }

    public function updateProfile(User $user, array $data): UserResource
    {
        $phoneNumberExist = $this->userRepo->getDuplicateUserByPhoneNumber($data['phone'], $user->id);

        if ($phoneNumberExist) {
            throw new CustomException('Phone number belongs to another user', 403);
        }

        return new UserResource($this->userRepo->updateProfile($data, $user));
    }

    public function updateBusinessProfile(User $user, array $data): UserResource
    {
        return new UserResource($this->userRepo->updateBusinessProfile($data, $user));
    }

    public function updatePassword(User $user, array $data): string
    {
        if (!$user || !Hash::check($data['current_password'], $user->password)) {
            throw new CustomException('Incorrect current password', 403);
        }

        $this->userRepo->updatePassword($data, $user);

        return 'Password successfully updated';
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

    public function logout(User $user): int
    {
        return $this->userRepo->logout($user);
    }
}
