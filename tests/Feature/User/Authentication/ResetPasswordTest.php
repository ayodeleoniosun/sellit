<?php

namespace Tests\Feature\User;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

test('cannot send forgot password link to non existent email', function () {
    $data = ['email' => 'invalid@email.com'];

    $response = $this->postJson($this->baseUrl.'/auth/forgot-password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('We can\'t find a user with that email address.', $responseJson->message);
});

test('send forgot password link to existing email', function () {
    Mail::fake();
    $data = ['email' => $this->createUser()->email];

    $response = $this->postJson($this->baseUrl.'/auth/forgot-password', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals($responseJson->message, 'We have emailed your password reset link!');
});

test('cannot reset password with empty token', function () {
    $data = ['token' => ''];

    $response = $this->postJson($this->baseUrl.'/auth/reset-password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The token field is required.', $responseJson->errors->token[0]);
});

test('cannot reset password with short passwords', function () {
    $data = [
        'password' => '12345',
        'password_confirmation' => '12345',
    ];

    $response = $this->postJson($this->baseUrl.'/auth/reset-password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The password must be at least 8 characters.', $responseJson->errors->password[0]);
});

test('cannot reset password with non matching passwords', function () {
    $data = [
        'password' => '1234567',
        'password_confirmation' => '12345678',
    ];

    $response = $this->postJson($this->baseUrl.'/auth/reset-password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('The password confirmation does not match.', $responseJson->errors->password[0]);
});

test('cannot reset password with an invalid token', function () {
    $data = [
        'email' => $this->createUser()->email,
        'password' => '12345678',
        'password_confirmation' => '12345678',
        'token' => Str::random(60),
    ];

    $response = $this->postJson($this->baseUrl.'/auth/reset-password', $data);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('This password reset token is invalid.', $responseJson->message);
});

test('can reset password', function () {
    $user = $this->createUser();
    $token = Password::createToken($user);

    $data = [
        'email' => $user->email,
        'token' => $token,
        'password' => $user->email,
        'password_confirmation' => $user->email,
    ];

    $response = $this->postJson($this->baseUrl.'/auth/reset-password', $data);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Your password has been reset!', $responseJson->message);
});
