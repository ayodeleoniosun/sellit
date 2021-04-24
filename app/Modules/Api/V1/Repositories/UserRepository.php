<?php

namespace App\Modules\Api\V1\Repositories;

interface UserRepository
{
    public function signUp(array $data);

    public function signIn(array $data);

    public function profile(string $token);

    public function updatePersonalInformation(array $data);

    public function updateBusinessInformation(array $data);

    public function changePassword(array $data);

    public function updateProfilePicture(array $data);
}
