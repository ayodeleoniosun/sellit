<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    actingAs($this->createAdminUser());
});

test('can view all users', function () {
    Storage::fake('s3');

    $response = $this->getJson($this->baseUrl.'/admin/users');
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'first_name', 'last_name', 'fullname', 'slug', 'email', 'phone', 'verified', 'business', 'profile', 'profile_picture', 'created_at'],
        ],
    ]);
});
