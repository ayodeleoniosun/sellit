<?php

namespace Tests\Unit\Repository;

use App\Models\File;
use App\Models\User;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Tests\Traits\CreateCities;
use Tests\Traits\CreateFiles;
use Tests\Traits\CreateStates;

uses(CreateStates::class, CreateCities::class, CreateFiles::class);

beforeEach(function () {
    $this->user = new User();
    $this->mockFile = new File();
    $this->fileRepo = \Mockery::mock(FileRepositoryInterface::class);
    $this->userRepo = new UserRepository($this->user, $this->fileRepo);
});

test('can get user by email address', function () {
    $user = $this->createUser();

    $response = $this->userRepo->getUserByEmailAddress($user->email);
    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals($user->email, $response->email);
});

test('can get invalid user by email address', function () {
    $invalidEmail = 'invalid@email.com';

    $response = $this->userRepo->getUserByEmailAddress($invalidEmail);
    $this->assertNull($response);
});

test('can get duplicate user by phone number', function () {
    $user1 = $this->createUser();
    $user2 = $this->createUser();

    $response = $this->userRepo->getDuplicateUserByPhoneNumber($user1->phone, $user2->id);
    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals($user1->phone, $response->phone);
});

test('cannot get duplicate user by phone number', function () {
    $user = $this->createUser();

    $response = $this->userRepo->getDuplicateUserByPhoneNumber($user->phone, $user->id);
    $this->assertNull($response);
});

test('can update user profile only', function () {
    $user = $this->createUser();

    $data = [
        'first_name' => $this->faker->name(),
        'last_name'  => $this->faker->name(),
        'phone'      => Str::random(11),
    ];

    $response = $this->userRepo->updateProfile($data, $user);
    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals($user->first_name, $response->first_name);
    $this->assertEquals($user->last_name, $response->last_name);
    $this->assertEquals($user->phone, $response->phone);
});

test('can update user profile with state and city', function () {
    $user = $this->createUser();
    $this->createState();
    $this->createCity();

    $data = [
        'first_name' => $this->faker->name(),
        'last_name'  => $this->faker->name(),
        'phone'      => Str::random(11),
        'state'      => 1,
        'city'       => 1,
    ];

    $response = $this->userRepo->updateProfile($data, $user);

    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals($user->first_name, $response->first_name);
    $this->assertEquals($user->last_name, $response->last_name);
    $this->assertEquals($user->phone, $response->phone);
    $this->assertEquals($user->profile->state_id, $response->profile->state_id);
    $this->assertEquals($user->profile->city_id, $response->profile->city_id);
});

test('can get user by slug', function () {
    $user = $this->createUser();

    $response = $this->userRepo->getUser($user->slug);

    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals($user->slug, $response->slug);
});

test('cannot get user by slug', function () {
    $response = $this->userRepo->getUser('invalid_slug');

    $this->assertNull($response);
});

test('can update user password', function () {
    $user = $this->createUser();

    $data = ['new_password' => 'new_password'];
    $this->userRepo->updatePassword($data, $user);

    //cannot assert anything because the method is void
});

test('can update profile picture', function () {
    $file = $this->createFile();
    $user = $this->createUser();

    $filePath = Str::random(11) . '.jpg';

    $this->mockFile->id = 1;
    $this->mockFile->path = $filePath;

    $this->fileRepo->shouldReceive('create')
        ->once()
        ->with(['path' => $this->mockFile->path, 'type' => 'user'])
        ->andReturn($this->mockFile);

    $response = $this->userRepo->updateProfilePicture($filePath, $user);

    $this->assertInstanceOf(User::class, $response);
    $this->assertEquals($response->pictures[0]->profile_picture_id, $file->id);
})->group('test');

test('can logout user', function () {
    $user = $this->createUser();

    $response = $this->userRepo->logout($user);
    $this->assertIsInt($response);
});
