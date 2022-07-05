<?php

namespace Tests\Feature\User;

use App\Mail\ForgotPasswordMail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\CreatePasswordResets;
use Tests\CreateUsers;

uses(RefreshDatabase::class, CreateUsers::class, CreatePasswordResets::class);

test('cannot send forgot password link to non existent email', function () {
    $data = ['email_address' => 'invalid@email.com'];
    $response = $this->postJson($this->apiBaseUrl . '/accounts/forgot-password', $data);

    $response->assertNotFound();

    $this->assertEquals('error', $response->getData()->status);
    $this->assertEquals('Email address does not exist', $response->getData()->message);
});

test('send forgot password link to existing email', function () {
    Mail::fake();

    $user = $this->createUser();
    $data = ['email_address' => $user->email_address];
    $response = $this->postJson($this->apiBaseUrl . '/accounts/forgot-password', $data);

    $response->assertOk();
    $this->assertEquals('success', $response->getData()->status);
    $this->assertEquals($response->getData()->message, 'Reset password link successfully sent to ' . $user->email_address);

    Mail::assertQueued(ForgotPasswordMail::class);
});

test('cannot reset password with empty token', function () {
    $data = ['token' => ''];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The token field is required.', $response->getData()->errors->token[0]);
});

test('cannot reset password with short passwords', function () {
    $data = [
        'new_password'              => '12345',
        'new_password_confirmation' => '12345',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The new password must be at least 8 characters.', $response->getData()->errors->new_password[0]);
});

test('cannot reset password with non matching passwords', function () {
    $data = [
        'new_password'              => '1234567',
        'new_password_confirmation' => '12345678',
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The new password confirmation does not match.', $response->getData()->errors->new_password[0]);
});

test('cannot reset password with non existent token', function () {
    $data = ['token' => Str::random(60)];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The selected token is invalid.', $response->getData()->errors->token[0]);
});

test('cannot reset password with invalid token', function () {
    $data = [
        'email_address' => 'invalid@sellit.test',
        'token'         => Str::random(60),
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertUnprocessable();
    $this->assertEquals('The selected token is invalid.', $response->getData()->errors->token[0]);
});

test('cannot reset password with expired token', function () {
    $user = $this->createUser();
    $passwordReset = $this->createPasswordReset();

    $passwordReset->email = $user->email_address;
    $passwordReset->created_at = now()->subMinute(70);
    $passwordReset->save();

    $data = [
        'email_address'             => $user->email_address,
        'token'                     => $passwordReset->token,
        'new_password'              => $user->email_address,
        'new_password_confirmation' => $user->email_address,
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertForbidden();
    $this->assertEquals('error', $response->getData()->status);
    $this->assertEquals('Token has expired. Kindly request for a forgot password link again.', $response->getData()->message);
});

test('can reset password', function () {
    $user = $this->createUser();
    $passwordReset = $this->createPasswordReset();

    $passwordReset->email = $user->email_address;
    $passwordReset->created_at = now();
    $passwordReset->save();

    $data = [
        'email_address'             => $user->email_address,
        'token'                     => $passwordReset->token,
        'new_password'              => $user->email_address,
        'new_password_confirmation' => $user->email_address,
    ];

    $response = $this->postJson($this->apiBaseUrl . '/accounts/reset-password', $data);

    $response->assertOk();
    $this->assertEquals('success', $response->getData()->status);
    $this->assertEquals('Password successfully reset', $response->getData()->message);
});
