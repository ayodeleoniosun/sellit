<?php

namespace Tests\Unit\Repository;

use App\Contracts\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\User\AuthRepository;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->user = new User();
    $this->userRepo = \Mockery::mock(UserRepositoryInterface::class);
    $this->authRepo = new AuthRepository($this->user, $this->userRepo);
});

test('create new user', function () {
    $user = $this->user->create([
        'first_name' => $this->faker->name(),
        'last_name'  => $this->faker->name(),
        'slug'       => Str::slug($this->faker->name()) . '-' . strtolower(Str::random(8)),
        'email'      => $this->faker->unique()->safeEmail(),
        'phone'      => Str::random(11),
        'password'   => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ]);

    $this->assertInstanceOf(User::class, $user);
});

test('get user by email address', function () {
    $user = $this->createUser();

    $this->userRepo->shouldReceive('getUserByEmailAddress')
        ->once()
        ->with($user->email)
        ->andReturn($user);

    $response = $this->authRepo->getUserByEmailAddress($user->email);
    $this->assertInstanceOf(User::class, $response);
});

test('get invalid user by email address', function () {
    $invalidEmail = 'invalid@email.com';

    $this->userRepo->shouldReceive('getUserByEmailAddress')
        ->once()
        ->with($invalidEmail)
        ->andReturnNull();

    $response = $this->authRepo->getUserByEmailAddress($invalidEmail);
    $this->assertNull($response);
});

test('create user token', function () {
    $user = $this->createUser();
    $response = $this->authRepo->createToken($user);
    $this->assertIsString($response);
});
