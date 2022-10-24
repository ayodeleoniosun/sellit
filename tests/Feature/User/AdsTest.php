<?php

namespace Tests\Feature;

use App\Jobs\UploadImage;
use Database\Seeders\CategorySeeder;
use Database\Seeders\SortOptionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(CategorySeeder::class);
    actingAs($this->createVerifiedUser());
});

test('can add new ads', function () {
    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Ads successfully added', $responseJson->message);

    $this->assertEquals(ucfirst($data['name']), $responseJson->data->name);
    $this->assertEquals(ucfirst($data['description']), $responseJson->data->description);
    $this->assertEquals($data['price'], $responseJson->data->price);

    $response->assertJsonStructure([
        'data' => [
            'id', 'name', 'slug', 'description', 'price',
            'seller' => [
                'id', 'first_name', 'last_name', 'slug', 'email', 'phone'
            ],
            'sort_options', 'pictures', 'total_rating'
        ]
    ]);
});

test('cannot update an unauthorized ads', function () {
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

    //create new ads from new seller
    actingAs($this->createVerifiedUser());
    $response = $this->postJson($this->baseUrl.'/users/ads', $data);
    $response->assertCreated();

    //attempt to update an authorized ads
    $response = $this->putJson($this->baseUrl.'/users/ads/'.$adsId, $data);
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Unauthorized', $responseJson->message);
});

test('can update existing ads', function () {
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

    //update existing ads
    $updatedData = [
        'category_id' => '3',
        'sub_category_id' => '2',
        'name' => 'update new ads',
        'price' => 6000,
        'description' => 'this is an updated description'
    ];

    $response = $this->putJson($this->baseUrl.'/users/ads/'.$adsId, $updatedData);
    $response->assertOk();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Ads successfully updated', $responseJson->message);

    $this->assertEquals($updatedData['category_id'], $responseJson->data->category->id);
    $this->assertEquals($updatedData['sub_category_id'], $responseJson->data->sub_category->id);
    $this->assertEquals(ucfirst($updatedData['name']), $responseJson->data->name);
    $this->assertEquals(ucfirst($updatedData['description']), $responseJson->data->description);
    $this->assertEquals($updatedData['price'], $responseJson->data->price);

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

test('can view user ads', function () {
    //add new ads

    $data = [
        'category_id' => '1',
        'sub_category_id' => '2',
        'name' => 'new ads',
        'price' => 5000,
        'description' => 'this is the description'
    ];

    $this->postJson($this->baseUrl.'/users/ads', $data);

    //View user ads
    $response = $this->getJson($this->baseUrl.'/users/ads');
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

test('cannot upload pictures that are more than 5', function () {
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

    //upload pictures
    Storage::fake('s3');

    $picturesData = [
        'pictures' => [
            UploadedFile::fake()->image('ads-picture-1.jpg'),
            UploadedFile::fake()->image('ads-picture-2.jpg'),
            UploadedFile::fake()->image('ads-picture-3.jpg'),
            UploadedFile::fake()->image('ads-picture-4.jpg'),
            UploadedFile::fake()->image('ads-picture-5.jpg'),
            UploadedFile::fake()->image('ads-picture-6.jpg'),
        ]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures', $picturesData);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('Maximum allowed number of images is 5', $responseJson->message);
});

test('cannot upload ads pictures that are not images', function () {
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

    //upload pictures
    Storage::fake('s3');

    $picturesData = [
        'pictures' => [
            UploadedFile::fake()->create('ads-picture-1.pdf')
        ]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures', $picturesData);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertStringContainsString('must be an image', $responseJson->message);
});

test('cannot upload ads pictures that are greater than 2MB', function () {
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

    //upload pictures
    Storage::fake('s3');

    $picturesData = [
        'pictures' => [
            UploadedFile::fake()->create('ads-picture-1.jpg',4000)
        ]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures', $picturesData);
    $response->assertUnprocessable();
    $responseJson = json_decode($response->content());

    $this->assertEquals('Maximum allowed size for an image is 2MB', $responseJson->message);
});

test('can upload valid ads pictures', function () {
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

    //upload pictures
    Storage::fake('s3');
    Queue::fake();

    $picturesData = [
        'pictures' => [
            UploadedFile::fake()->image('ads-picture-1.jpg'),
            UploadedFile::fake()->image('ads-picture-2.jpg'),
            UploadedFile::fake()->image('ads-picture-3.jpg'),
        ]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures', $picturesData);
    $response->assertCreated();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Ads pictures successfully uploaded', $responseJson->message);
    $this->assertCount(3, $responseJson->data->pictures);

    Queue::assertPushed(UploadImage::class);
});

test('cannot delete a non-existent picture', function () {
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

    //delete ads picture
    $response = $this->deleteJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures/3');
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Invalid resource', $responseJson->message);
});

test('cannot delete picture from a non-existent ads', function () {
    $response = $this->deleteJson($this->baseUrl.'/users/ads/2/pictures/3');
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Ads does not exist', $responseJson->message);
});

test('cannot delete an unauthorized picture', function () {
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

    //create new ads from new seller
    actingAs($this->createVerifiedUser());
    $response = $this->postJson($this->baseUrl.'/users/ads', $data);
    $response->assertCreated();

    //attempt to delete an unauthorized ads picture
    $response = $this->deleteJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures/3');
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('Unauthorized', $responseJson->message);
});

test('can delete ads picture', function () {
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

    //upload pictures
    Storage::fake('s3');

    $picturesData = [
        'pictures' => [
            UploadedFile::fake()->image('ads-picture-1.jpg')
        ]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures', $picturesData);
    $response->assertCreated();
    $pictureId = json_decode($response->content())->data->pictures[0]->id;

    //delete ads picture
    $response = $this->deleteJson($this->baseUrl.'/users/ads/'.$adsId.'/pictures/'.$pictureId);
    $response->assertNoContent();
    $responseJson = $response->getData();

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Ads picture successfully deleted', $responseJson->message);
});

test('can add new sort option values', function () {
    $this->seed(SortOptionSeeder::class);

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

    //create new sort option values
    $data = [
        'sort_option_values' => [1,2,3]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/sort-options', $data);
    $response->assertCreated();
    $responseJson = json_decode($response->content());

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('3 sort options successfully added', $responseJson->message);
});

test('cannot add new sort option values if it has been added before', function () {
    $this->seed(SortOptionSeeder::class);

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

    //create new sort option values
    $data = [
        'sort_option_values' => [1,2,3]
    ];

    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/sort-options', $data);
    $response->assertCreated();

    //attempt to create new sort option values from existing ones
    $response = $this->postJson($this->baseUrl.'/users/ads/'.$adsId.'/sort-options', $data);
    $responseJson = json_decode($response->content());

    $this->assertEquals('error', $responseJson->status);
    $this->assertEquals('No sort options added', $responseJson->message);
});

test('can delete ads', function () {
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

    //delete ads
    $response = $this->deleteJson($this->baseUrl.'/users/ads/'.$adsId);
    $responseJson = $response->getData();

    $this->assertEquals('success', $responseJson->status);
    $this->assertEquals('Ads successfully deleted', $responseJson->message);
});
