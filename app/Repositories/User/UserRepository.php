<?php

namespace App\Repositories\User;

use App\Entities\Repositories\FileRepositoryInterface;
use App\Entities\Repositories\User\UserRepositoryInterface;
use App\Models\BusinessProfile;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    private User $user;

    protected FileRepositoryInterface $fileRepo;

    public function __construct(User $user, FileRepositoryInterface $fileRepo)
    {
        $this->user = $user;
        $this->fileRepo = $fileRepo;
    }

    public function getUsers(Request $request): Collection
    {
        return User::with('profile', 'businessProfile', 'pictures')->latest()->get();
    }

    public function getUser(string $slug): ?User
    {
        $user = $this->user->where('slug', $slug);

        if ($user->first()) {
            return $user->with('profile', 'businessProfile', 'pictures')->first();
        }

        return null;
    }

    public function getUserByEmailAddress(string $email): ?User
    {
        return $this->user->where('email', $email)?->first();
    }

    public function getDuplicateUserByPhoneNumber(string $phone, int $id): ?User
    {
        return $this->user->where('phone', $phone)->where('id', '<>', $id)->first();
    }

    public function updateProfile(array $data, User $user): User
    {
        $user->update($data);

        if (isset($data['state']) && isset($data['city'])) {
            $this->updateUserProfile($data, $user);
        }

        $user->refresh();

        return $this->getUser($user->slug);
    }

    public function updateUserProfile(array $data, User $user): void
    {
         UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['state_id' => $data['state'], 'city_id' => $data['city'] ]
        );
    }

    public function updateBusinessProfile(array $data, User $user): User
    {
        if (!$user->businessProfile) {
            $user->businessProfile = new BusinessProfile();
            $user->businessProfile->user_id = $user->id;
        }

        $user->businessProfile->name = $data['name'];
        $user->businessProfile->slug = strtolower($data['slug']);
        $user->businessProfile->description = $data['description'];
        $user->businessProfile->address = $data['address'];
        $user->businessProfile->id ? $user->businessProfile->update() : $user->businessProfile->save();

        $user->refresh();

        return $this->getUser($user->slug);
    }

    public function updatePassword(array $data, User $user): User
    {
        $user->password = bcrypt($data['new_password']);
        $user->update();

        return $user;
    }

    public function updateProfilePicture(string $filename, User $user): User
    {
        $file = $this->fileRepo->create(['path' => $filename]);

        $user->pictures = new UserProfilePicture();
        $user->pictures->user_id = $user->id;
        $user->pictures->profile_picture_id = $file->id;
        $user->pictures->save();

        $user->fresh();

        return $this->getUser($user->slug);
    }

    public function logout(User $user): int
    {
        return $user->tokens()->delete();
    }
}
