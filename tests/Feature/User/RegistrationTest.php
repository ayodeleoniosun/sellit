<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreateUsers;

uses(RefreshDatabase::class, CreateUsers::class);

test('cannot register with short password', function () {
    $data = [
        'first_name'    => 'firstname',
        'last_name'     => 'lastname',
        'email_address' => 'email@sellit.test',
        'phone_number'  => '08123456789',
        'password'      => '12345',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The password must be at least 8 characters.', $response->getData()->message);
    $this->assertEquals('The password must be at least 8 characters.', $response->getData()->errors->password[0]);
});

test('cannot register if lastname and email address is empty', function () {
    $data = [
        'first_name'   => 'firstname',
        'phone_number' => '08123456789',
        'password'     => '1234567',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The last name field is required. (and 2 more errors)', $response->getData()->message);
    $this->assertEquals('The last name field is required.', $response->getData()->errors->last_name[0]);
    $this->assertEquals('The email address field is required.', $response->getData()->errors->email_address[0]);
});

test('cannot register if email address or phone number exist', function () {
    $user = $this->createUser();
    $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $user->getAttributes());

    $response->assertUnprocessable();
    $this->assertEquals('The email address has already been taken. (and 1 more error)', $response->getData()->message);
    $this->assertEquals('The phone number has already been taken.', $response->getData()->errors->phone_number[0]);
    $this->assertEquals('The email address has already been taken.', $response->getData()->errors->email_address[0]);
});

test('can register new user', function () {
    $data = [
        'first_name'    => 'firstname',
        'last_name'     => 'lastname',
        'email_address' => 'email@sellit.test',
        'phone_number'  => '08123456789',
        'password'      => 'email@sellit.test',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);

    $response->assertCreated();
    $this->assertEquals('success', $response->getData()->status);
    $this->assertEquals('Registration successful', $response->getData()->message);
    $this->assertEquals($data['first_name'], $response->getData()->data->first_name);
    $this->assertEquals($data['last_name'], $response->getData()->data->last_name);
    $this->assertEquals($data['email_address'], $response->getData()->data->email_address);
    $this->assertEquals($data['phone_number'], $response->getData()->data->phone_number);
});

