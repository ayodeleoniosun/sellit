<?php

namespace Tests\Feature\User;

test('cannot login with invalid credentials', function () {
    $data = [
        'email' => 'email@sellit.test',
        'password'      => 'email@sellit.test',
    ];

    $response = $this->postJson($this->baseUrl . '/auth/login', $data);
    $response->assertUnauthorized();
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Incorrect login credentials', $responseJson->message);
});

test('can login with valid credentials', function () {
    $user = $this->createVerifiedUser();

    $data = ['email' => $user->email, 'password' => 'password'];

    $response = $this->postJson($this->baseUrl . '/auth/login', $data);
    $responseJson = json_decode($response->content());

    $response->assertOk()
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => ['id', 'first_name', 'last_name', 'email', 'slug', 'phone',
                    'verified', 'created_at', 'updated_at',
                ],
                'token',
            ],
        ]);

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Login successful.', $responseJson->message);
});
