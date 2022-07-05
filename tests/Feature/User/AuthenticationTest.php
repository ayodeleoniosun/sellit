<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreateUsers;

uses(RefreshDatabase::class, CreateUsers::class);

test('cannot login with invalid credentials', function () {
    $data = [
        'email_address' => 'email@sellit.test',
        'password'      => 'email@sellit.test',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/login', $data);

    $response->assertUnauthorized();
    $this->assertEquals('error', $response->getData()->status);
    $this->assertEquals('Incorrect login credentials', $response->getData()->message);
});

test('can login with valid credentials', function () {
    $user = $this->createUser();

    $data = ['email_address' => $user->email_address, 'password' => 'password'];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/login', $data);

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => ['id', 'first_name', 'last_name', 'email_address', 'slug', 'phone_number',
                    'verified', 'created_at', 'updated_at',
                ],
                'token',
            ],
        ]);

    $this->assertEquals('success', $response->getData()->status);
    $this->assertEquals('Login successful', $response->getData()->message);
});
