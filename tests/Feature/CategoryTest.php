<?php

namespace Tests\Feature;

uses(CreateStates::class, CreateCities::class);

beforeEach(function () {
    $this->user = actingAs($this->createVerifiedUser());
});

test('can view profile', function () {
    $response = $this->getJson($this->baseUrl.'/users/'.$this->user->slug);
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
