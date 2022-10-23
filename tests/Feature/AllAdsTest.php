<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;

beforeEach(function () {
    $this->seed(CategorySeeder::class);
    actingAs($this->createVerifiedUser());
});

test('can view all ads', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //view all ads
    $response = $this->getJson($this->baseUrl.'/ads');
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'category' => [
                    'id', 'name', 'slug'
                ],
                'sub_category' => [
                    'id', 'category_id', 'name', 'slug'
                ],
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});

test('can view single ads', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads', $data);
    $response->assertCreated();
    $adsId = json_decode($response->content())->data->id;

    //view ads details
    $response = $this->getJson($this->baseUrl.'/ads/'.$adsId);
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            'id', 'name', 'slug', 'description', 'price',
            'category' => [
                'id', 'name', 'slug'
            ],
            'sub_category' => [
                'id', 'category_id', 'name', 'slug'
            ],
            'seller' => [
                'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
            ],
            'sort_options', 'pictures', 'total_rating'
        ]
    ]);
});

test('can view all category ads', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //view all category ads
    $response = $this->getJson($this->baseUrl.'/ads/category/'.$data['category_id']);
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});

test('can view all sub category ads', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //view all sub category ads
    $response = $this->getJson($this->baseUrl.'/ads/category/'.$data['category_id'].'/sub-category/'.$data['sub_category_id']);
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});

test('can filter latest ads', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //create another ads
    $data['name'] = 'another new ads';
    $data['price'] = 3000;

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //filter latest ads
    $response = $this->getJson($this->baseUrl.'/ads?type=newest');
    $responseJson = json_decode($response->content());
    $response->assertOk();

    $this->assertGreaterThan($responseJson->data[1]->id, $responseJson->data[0]->id);

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});

test('can filter oldest ads', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //create another ads
    $data['name'] = 'another new ads';
    $data['price'] = 3000;

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //filter latest ads
    $response = $this->getJson($this->baseUrl.'/ads?type=oldest');
    $responseJson = json_decode($response->content());
    $response->assertOk();

    $this->assertGreaterThan($responseJson->data[0]->id, $responseJson->data[1]->id);

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});

test('can filter ads by lowest price', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //create another ads
    $data['name'] = 'another new ads';
    $data['price'] = 3000;

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //filter latest ads
    $response = $this->getJson($this->baseUrl.'/ads?price=lowest');
    $responseJson = json_decode($response->content());
    $response->assertOk();

    $this->assertGreaterThan($responseJson->data[0]->price, $responseJson->data[1]->price);

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});

test('can filter ads by highest price', function () {
    //add new ads
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //create another ads
    $data['name'] = 'another new ads';
    $data['price'] = 3000;

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //filter latest ads
    $response = $this->getJson($this->baseUrl.'/ads?price=highest');
    $responseJson = json_decode($response->content());
    $response->assertOk();

    $this->assertGreaterThan($responseJson->data[1]->price, $responseJson->data[0]->price);

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id', 'name', 'slug', 'description', 'price',
                'seller' => [
                    'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
                ],
                'sort_options', 'pictures', 'total_rating'
            ]
        ]
    ]);
});
