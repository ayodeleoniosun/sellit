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
    $responseJson = json_decode($response->content());

    $this->assertEquals('The password must be at least 8 characters.', $responseJson->message);
    $this->assertEquals('The password must be at least 8 characters.', $responseJson->errors->password[0]);
});

test('cannot register if lastname and email address is empty', function () {
    $data = [
        'first_name'   => 'firstname',
        'phone_number' => '08123456789',
        'password'     => '1234567',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The last name field is required. (and 2 more errors)', $responseJson->message);
    $this->assertEquals('The last name field is required.', $responseJson->errors->last_name[0]);
    $this->assertEquals('The email address field is required.', $responseJson->errors->email_address[0]);
});

test('cannot register if email address or phone number exist', function () {
    $user = $this->createUser();

    $response = $this->postJson($this->apiBaseUrl . '/accounts/register', $user->getAttributes());
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The email address has already been taken. (and 1 more error)', $responseJson->message);
    $this->assertEquals('The phone number has already been taken.', $responseJson->errors->phone_number[0]);
    $this->assertEquals('The email address has already been taken.', $responseJson->errors->email_address[0]);
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
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Registration successful', $responseJson->message);
    $this->assertEquals($data['first_name'], $responseJson->data->first_name);
    $this->assertEquals($data['last_name'], $responseJson->data->last_name);
    $this->assertEquals($data['email_address'], $responseJson->data->email_address);
    $this->assertEquals($data['phone_number'], $responseJson->data->phone_number);
});

