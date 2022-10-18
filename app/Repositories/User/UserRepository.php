<?php

namespace App\Repositories\User;

use App\Contracts\Repositories\File\FileRepositoryInterface;
use App\Contracts\Repositories\User\UserRepositoryInterface;
use App\Models\BusinessProfile;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserProfilePicture;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    private User $user;

    private UserProfile $userProfile;

    private BusinessProfile $businessProfile;

    private UserProfilePicture $userProfilePicture;

    private FileRepositoryInterface $fileRepo;

    public function __construct(
        User $user,
        UserProfile $userProfile,
        BusinessProfile $businessProfile,
        UserProfilePicture $userProfilePicture,
        FileRepositoryInterface $fileRepo
    ) {
        parent::__construct($user);

        $this->user = $user;
        $this->userProfile = $userProfile;
        $this->businessProfile = $businessProfile;
        $this->userProfilePicture = $userProfilePicture;
        $this->fileRepo = $fileRepo;
    }

    public function getUser(int $userId): ?Model
    {
        return $this->find($userId, ['profile', 'businessProfile', 'pictures']);
    }

    public function getUserBySlug(string $slug): ?User
    {
        return $this->user->where('slug', $slug)->with('profile', 'businessProfile', 'pictures')->first();
    }

    public function getUserByEmailAddress(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function phoneExist(string $phone, int $id): bool
    {
        return $this->user->where('phone', $phone)->where('id', '<>', $id)->exists();
    }

    public function businessExist(string $name, int $userId): bool
    {
        return $this->businessProfile->where('name', $name)->where('user_id', '<>', $userId)->exists();
    }

    public function updateProfile(array $data, User $user): Model
    {
        $user->update($data);

        if (isset($data['state']) && isset($data['city'])) {
            $this->updateUserProfile($data, $user);
        }

        return $this->getUser($user->id);
    }

    public function updateUserProfile(array $data, User $user): void
    {
        $this->userProfile->updateOrCreate(
            ['user_id' => $user->id],
            ['state_id' => $data['state'], 'city_id' => $data['city']]
        );
    }

    public function updateBusinessProfile(array $data, User $user): Model
    {
        $this->businessProfile->updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $data['name'],
                'slug' => Str::kebab($data['name']),
                'description' => $data['description'],
                'address' => $data['address'],
            ]
        );

        return $this->getUser($user->id);
    }

    public function updateProfilePicture(string $filename, User $user): Model
    {
        $file = $this->fileRepo->create(['path' => $filename]);

        $this->userProfilePicture->create([
            'user_id' => $user->id,
            'profile_picture_id' => $file->id,
        ]);

        return $this->getUser($user->id);
    }

    public function logout(User $user): int
    {
        return $user->tokens()->delete();
    }
}
