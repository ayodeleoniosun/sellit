<?php

namespace Tests\Feature\User;

use App\Http\Resources\UserResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\CreateUsers;

uses(RefreshDatabase::class, CreateUsers::class);

beforeEach(function () {
    $this->user = actingAs($this->createUser());
});

test('can view profile', function () {
    $response = $this->getJson($this->apiBaseUrl . '/users/' . $this->user->slug);
    $response->assertOk();

    $resource = new UserResource($this->user);
    $data = $resource->response($response)->getData()->data;
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals($data->first_name, $this->user->first_name);
    $this->assertEquals($data->last_name, $this->user->last_name);
    $this->assertEquals($data->fullname, $this->user->fullname);
    $this->assertEquals($data->slug, $this->user->slug);
    $this->assertEquals($data->email_address, $this->user->email_address);
    $this->assertEquals($data->phone_number, $this->user->phone_number);
});

test('cannot update profile with empty fields', function () {
    $data = [
        'first_name'   => 'firstname',
        'phone_number' => '08123456789',
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/personal-information', $data);

    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The last name field is required.', $responseJson->message);
    $this->assertEquals('The last name field is required.', $responseJson->errors->last_name[0]);
});

test('cannot update profile with existing phone number', function () {
    $user = $this->createUser();

    $data = [
        'first_name'   => 'firstname',
        'last_name'    => 'lastname',
        'phone_number' => $user->phone_number,
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/personal-information', $data);
    $response->assertForbidden();
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Phone number belongs to another user', $responseJson->message);
});

test('can update profile', function () {
    $data = [
        'first_name'   => 'new firstname',
        'last_name'    => 'new lastname',
        'phone_number' => Str::random(11),
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/personal-information', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Profile successfully updated', $responseJson->message);
    $this->assertEquals($responseJson->data->first_name, ucfirst($data['first_name']));
    $this->assertEquals($responseJson->data->last_name, ucfirst($data['last_name']));
    $this->assertEquals($responseJson->data->fullname, ucwords($data['first_name'] . ' ' . $data['last_name']));
    $this->assertEquals($responseJson->data->phone_number, $data['phone_number']);
});

test('cannot update password with wrong current password', function () {
    $data = [
        'current_password'          => '12345678',
        'new_password'              => 'password@sellit.test',
        'new_password_confirmation' => 'password@sellit.test',
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/password', $data);
    $response->assertForbidden();
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Incorrect current password', $responseJson->message);
});

test('cannot update password with short passwords', function () {
    $data = [
        'new_password'              => '1234567',
        'new_password_confirmation' => '1234567',
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The new password must be at least 8 characters.', $responseJson->errors->new_password[0]);
});

test('cannot update password with non matching passwords', function () {
    $data = [
        'new_password'              => '1234567',
        'new_password_confirmation' => '12345678',
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The new password confirmation does not match.', $responseJson->errors->new_password[0]);
});

test('can update password', function () {
    $data = [
        'current_password'          => 'password',
        'new_password'              => 'password@sellit.test',
        'new_password_confirmation' => 'password@sellit.test',
    ];

    $response = $this->putJson($this->apiBaseUrl . '/users/profile/update/password', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Password successfully updated', $responseJson->message);
});

test('cannot update profile picture with invalid file', function () {
    $data = ['image' => 'filename.jpg'];

    $response = $this->postJson($this->apiBaseUrl . '/users/profile/update/picture', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The image must be an image.', $responseJson->errors->image[0]);
    $this->assertEquals('The image must be a file of type: jpeg, png, jpg.', $responseJson->errors->image[1]);
});

test('can_update_profile_picture', function () {
    Storage::fake('s3');
    $file = UploadedFile::fake()->image('avatar.png');
    $data = ['image' => $file];

    $response = $this->postJson($this->apiBaseUrl . '/users/profile/update/picture', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Profile picture successfully updated', $responseJson->message);
    $this->assertNotNull($responseJson->data->profile_picture);
});
