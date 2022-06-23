<?php

namespace App\Repositories;

interface UserRepository
{
    public function signUp(array $data);

    public function signIn(array $data, $user_type);

    public function users();

    public function profile(string $token);

    public function userProfile(string $user);

    public function updatePersonalInformation(array $data);

    public function updateBusinessInformation(array $data);

    public function changePassword(array $data);

    public function updateProfilePicture(array $data);
}
