<?php

namespace App\Services;

use App\Contracts\Repositories\User\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Exceptions\CustomException;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        $users = $this->userRepo->all(['profile', 'businessProfile', 'pictures']);

        return new UserCollection($users);
    }

    /**
     * @throws CustomException
     */
    public function profile(array $data): UserResource
    {
        $user = $this->userRepo->getUserBySlug($data['slug']);

        if (!$user) {
            throw new CustomException('User not found', 404);
        }

        return new UserResource($user);
    }

    /**
     * @throws CustomException
     */
    public function updateProfile(User $user, array $data): UserResource
    {
        $phoneNumberExist = $this->userRepo->phoneExist($data['phone'], $user->id);

        if ($phoneNumberExist) {
            throw new CustomException('Phone number belongs to another user');
        }

        return new UserResource($this->userRepo->updateProfile($data, $user));
    }

    /**
     * @throws CustomException
     */
    public function updateBusinessProfile(User $user, array $data): UserResource
    {
        $businessExist = $this->userRepo->businessExist($data['name'], $user->id);

        if ($businessExist) {
            throw new CustomException('Business name already exist');
        }

        return new UserResource($this->userRepo->updateBusinessProfile($data, $user));
    }

    public function updatePassword(int $userId,string $newPassword): string
    {
        $data['password'] = Hash::make($newPassword);

        $this->userRepo->update($userId, $data);

        return 'Password successfully updated';
    }

    public function updateProfilePicture(User $user, UploadedFile $picture): UserResource
    {
        $picture = (object) $picture;
        $extension = $picture->extension();
        $filename = $user->id . '' . time() . '.' . $extension;

        Storage::disk('s3')->put($filename, file_get_contents($picture->getRealPath()));

        return new UserResource($this->userRepo->updateProfilePicture($filename, $user));
    }

    public function logout(User $user): int
    {
        return $this->userRepo->logout($user);
    }
}
