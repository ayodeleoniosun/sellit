<?php

namespace Tests\Feature;

use Database\Seeders\CategorySeeder;
use Database\Seeders\SortOptionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(CategorySeeder::class);
});

test('can view all categories', function () {
    Storage::fake('s3');
    $response = $this->getJson($this->baseUrl.'/categories');
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertCount(10, $responseJson->data->categories);

    $response->assertJsonStructure([
        'data' => [
            'categories' => [
                '*' => ['id', 'name', 'slug', 'icon', 'total_sub_categories'],
            ],
        ],
    ]);
});

test('can view all sub categories of a category', function () {
    $response = $this->getJson($this->baseUrl.'/categories/1/sub-categories');
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            'sub_categories' => [
                '*' => ['id', 'name', 'slug',
                    'category' => [
                        'id', 'name', 'slug'
                    ]
                ],
            ],
        ],
    ]);
});

test('category does not have any sub category', function () {
    $response = $this->getJson($this->baseUrl.'/categories/100/sub-categories');
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertCount(0, $responseJson->data->sub_categories);
});

test('unauthorized to add new category', function () {
    Storage::fake('s3');
    $icon = UploadedFile::fake()->image('category-icon.png');

    $data = [
        'name' => 'new category',
        'icon' => $icon
    ];

    $response = $this->postJson($this->baseUrl.'/admin/categories', $data);
    $response->assertUnauthorized();
    $responseJson = json_decode($response->content());

    $this->assertEquals('Unauthenticated.', $responseJson->message);
});

test('can add new category', function () {
    actingAs($this->createAdminUser());
    Storage::fake('s3');
    $icon = UploadedFile::fake()->image('category-icon.png');

    $data = [
        'name' => 'new category',
        'icon' => $icon
    ];

    $response = $this->postJson($this->baseUrl.'/admin/categories', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Category successfully added', $responseJson->message);
    $this->assertEquals(ucfirst($data['name']), $responseJson->data->name);

    $response->assertJsonStructure([
        'data' => ['id', 'name', 'slug', 'icon', 'total_sub_categories']
    ]);
});

test('can update existing category', function () {
    actingAs($this->createAdminUser());

    //add new category
    Storage::fake('s3');
    $icon = UploadedFile::fake()->image('category-icon.png');

    $data = [
        'name' => 'new category',
        'icon' => $icon
    ];

    $response = $this->postJson($this->baseUrl.'/admin/categories', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());
    $slug = $responseJson->data->slug;

    //update existing category
    $updatedData = [
        'name' => 'update new category',
    ];

    $response = $this->postJson($this->baseUrl.'/admin/categories/'.$slug, $updatedData);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Category successfully updated', $responseJson->message);
    $this->assertEquals(ucfirst($updatedData['name']), $responseJson->data->name);
});

test('can add new sub category', function () {
    actingAs($this->createAdminUser());

    $data = [
        'name' => 'new sub category',
        'category_id' => '1'
    ];

    $response = $this->postJson($this->baseUrl.'/admin/sub-categories', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Sub category successfully added', $responseJson->message);
    $this->assertEquals(ucfirst($data['name']), $responseJson->data->name);

    $response->assertJsonStructure([
        'data' => ['id', 'name', 'slug']
    ]);
});

test('can update existing sub category', function () {
    actingAs($this->createAdminUser());

    //add new sub category
    $data = [
        'name' => 'new sub category',
        'category_id' => '1'
    ];

    $response = $this->postJson($this->baseUrl.'/admin/sub-categories', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());
    $slug = $responseJson->data->slug;

    //update existing sub category
    $updatedData = [
        'category_id' => '1',
        'name' => 'update new sub category',
    ];

    $response = $this->putJson($this->baseUrl.'/admin/sub-categories/'.$slug, $updatedData);
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Sub category successfully updated', $responseJson->message);
    $this->assertEquals(ucfirst($updatedData['name']), $responseJson->data->name);
});

test('can add new sub category sort options', function () {
    actingAs($this->createAdminUser());
    $this->seed(SortOptionSeeder::class);

    $data = [
        'sort_options' => [1,2,3]
    ];

    $response = $this->postJson($this->baseUrl.'/admin/sub-categories/sort-options/1', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertStringContainsString('3 sort options successfully added', $responseJson->message);
});

test('can add only new sort options to existing sub category sort options', function () {
    actingAs($this->createAdminUser());
    $this->seed(SortOptionSeeder::class);

    //add new sub category sort options
    $data = [
        'sort_options' => [1,2,3]
    ];

    $response = $this->postJson($this->baseUrl.'/admin/sub-categories/sort-options/1', $data);
    $response->assertCreated();

    //update existing sub category sort options
    $updatedData = [
        'sort_options' => [1,2,3,4,5]
    ];

    $response = $this->putJson($this->baseUrl.'/admin/sub-categories/sort-options/1', $updatedData);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertStringContainsString('0 sort options removed and 2 new sort options successfully added', $responseJson->message);
});

test('can remove existing sort options and add new sort options to existing sub category sort options', function () {
    actingAs($this->createAdminUser());
    $this->seed(SortOptionSeeder::class);

    //add new sub category sort options
    $data = [
        'sort_options' => [1,2,3]
    ];

    $response = $this->postJson($this->baseUrl.'/admin/sub-categories/sort-options/1', $data);
    $response->assertCreated();

    //update existing sub category sort options
    $updatedData = [
        'sort_options' => [4,5]
    ];

    $response = $this->putJson($this->baseUrl.'/admin/sub-categories/sort-options/1', $updatedData);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertStringContainsString('3 sort options removed and 2 new sort options successfully added', $responseJson->message);
});

test('can view all sort options', function () {
    actingAs($this->createAdminUser());
    $this->seed(SortOptionSeeder::class);

    $response = $this->getJson($this->baseUrl.'/admin/sort-options');
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'slug', 'created_at', 'updated_at']
        ]
    ]);
});

test('can view all sort option values', function () {
    actingAs($this->createAdminUser());
    $this->seed(SortOptionSeeder::class);

    $response = $this->getJson($this->baseUrl.'/admin/sort-option-values/1');
    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'sort_option_id', 'type', 'value', 'created_at', 'updated_at']
        ]
    ]);
});

test('can view all sub category sort options', function () {
    actingAs($this->createAdminUser());
    $this->seed(SortOptionSeeder::class);

    //add new sub category sort options
    $data = [
        'sort_options' => [1,2,3,4,5]
    ];

    $response = $this->postJson($this->baseUrl.'/admin/sub-categories/sort-options/1', $data);
    $response->assertCreated();

    //view all sub category sort options
    $response = $this->getJson($this->baseUrl.'/sub-categories/1/sort-options');
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertCount(count($data['sort_options']), $responseJson->data);

    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'sort_option' => [
                'id', 'name', 'slug', 'created_at', 'updated_at'
            ]
            ],
        ],
    ]);
});
