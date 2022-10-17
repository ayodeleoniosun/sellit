<?php

namespace Tests\Unit\Repository;

use App\Contracts\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\User\AuthRepository;

beforeEach(function () {
    $this->user = new User();
    $this->userRepo = \Mockery::mock(UserRepositoryInterface::class);
    $this->authRepo = new AuthRepository($this->user, $this->userRepo);
});

test('create user token', function () {
    $user = $this->createUser();
    $response = $this->authRepo->createToken($user);
    $this->assertIsString($response);
});
