<?php

namespace Tests\Feature\User;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Traits\CreateCities;
use Tests\Traits\CreateStates;

uses(CreateStates::class, CreateCities::class);

beforeEach(function () {
    $this->user = actingAs($this->createUser());
});

test('can view profile', function () {
    $response = $this->getJson($this->baseUrl . '/users/' . $this->user->slug);
    $response->assertOk();

    $resource = new UserResource($this->user);
    $data = $resource->response($response)->getData()->data;
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals($data->first_name, $this->user->first_name);
    $this->assertEquals($data->last_name, $this->user->last_name);
    $this->assertEquals($data->fullname, $this->user->fullname);
    $this->assertEquals($data->slug, $this->user->slug);
    $this->assertEquals($data->email, $this->user->email);
    $this->assertEquals($data->phone, $this->user->phone);
});

test('cannot update profile with empty fields', function () {
    $data = [
        'first_name' => 'firstname',
        'phone'      => '08123456789',
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/personal-information', $data);

    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The last name field is required.', $responseJson->errors->last_name[0]);
});

test('cannot update profile with existing phone number', function () {
    $user = $this->createUser();
    $state = $this->createState();
    $city = $this->createCity();

    $data = [
        'first_name' => 'firstname',
        'last_name'  => 'lastname',
        'phone'      => $user->phone,
        'state'      => $state->id,
        'city'       => $city->id,
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/personal-information', $data);
    $response->assertForbidden();
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Phone number belongs to another user', $responseJson->message);
});

test('can update profile', function () {
    $state = $this->createState();
    $city = $this->createCity();

    $data = [
        'first_name' => 'new firstname',
        'last_name'  => 'new lastname',
        'phone'      => Str::random(11),
        'state'      => $state->id,
        'city'       => $city->id,
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/personal-information', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Profile successfully updated', $responseJson->message);
    $this->assertEquals($responseJson->data->first_name, ucfirst($data['first_name']));
    $this->assertEquals($responseJson->data->last_name, ucfirst($data['last_name']));
    $this->assertEquals($responseJson->data->fullname, ucwords($data['first_name'] . ' ' . $data['last_name']));
    $this->assertEquals($responseJson->data->phone, $data['phone']);
});

test('cannot update password with wrong current password', function () {
    $data = [
        'current_password'          => '12345678',
        'new_password'              => ',password_123456789',
        'new_password_confirmation' => ',password_123456789',
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The current password is incorrect', $responseJson->errors->current_password[0]);
});

test('cannot update password with short passwords', function () {
    $data = [
        'current_password'          => '12345678',
        'new_password'              => '123456',
        'new_password_confirmation' => '123456',
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The new password must be at least 8 characters.', $responseJson->errors->new_password[0]);
});

test('cannot update password with non matching passwords', function () {
    $data = [
        'current_password'          => '12345678',
        'new_password'              => '12345',
        'new_password_confirmation' => '123456',
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The new password confirmation does not match.', $responseJson->errors->new_password[0]);
});

test('cannot update password with weak passwords', function () {
    $data = [
        'current_password'          => '12345678',
        'new_password'              => '123456',
        'new_password_confirmation' => '123456',
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The new password must be at least 8 characters.', $responseJson->errors->new_password[0]);
    $this->assertEquals('The new password must contain at least one uppercase and one lowercase letter.', $responseJson->errors->new_password[1]);
    $this->assertEquals('The new password must contain at least one letter.', $responseJson->errors->new_password[2]);
    $this->assertEquals('The new password must contain at least one symbol.', $responseJson->errors->new_password[3]);
})->group('test');

test('can update password', function () {
    $data = [
        'current_password'          => 'password',
        'new_password'              => 'password@123A',
        'new_password_confirmation' => 'password@123A',
    ];

    $response = $this->putJson($this->baseUrl . '/users/profile/update/password', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Password successfully updated', $responseJson->message);
});

test('cannot update profile picture with invalid file', function () {
    $data = ['image' => 'filename.jpg'];

    $response = $this->postJson($this->baseUrl . '/users/profile/update/picture', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The image must be an image.', $responseJson->errors->image[0]);
    $this->assertEquals('The image must be a file of type: jpeg, png, jpg.', $responseJson->errors->image[1]);
});

test('can update profile picture', function () {
    Storage::fake('s3');
    $file = UploadedFile::fake()->image('avatar.png');
    $data = ['image' => $file];

    $response = $this->postJson($this->baseUrl . '/users/profile/update/picture', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Profile picture successfully updated', $responseJson->message);
    $this->assertNotNull($responseJson->data->profile_picture);
});
