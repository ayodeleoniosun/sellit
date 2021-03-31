<?php

namespace Tests\V1\Feature;

use App\Jobs\SendUserWelcomeMail;
use Tests\V1\Traits\User as TraitsUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\V1\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions, TraitsUser;

    public function setUp(): void
    {
        parent::setup();
    }

    public function testRequiredDetailsPresentInAccountCreation()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_address' => $this->faker->email,
            'password' => 'secret',
            'phone_number' => ''
        ];

        $response = $this->json('POST', $this->route("/account/signup"), $data);
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Phone number is required');
    }

    public function testPasswordMinimumCharacters()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_address' => $this->faker->email,
            'password' => 'secre',
            'phone_number' => '080'.rand(111111111, 999999999),
        ];

        $response = $this->json('POST', $this->route("/account/signup"), $data);
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'The password must be at least 6 characters.');
    }

    public function testPhoneNumberMinimumCharacters()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_address' => $this->faker->email,
            'password' => 'secret',
            'phone_number' => '080'.rand(111, 999),
        ];

        $response = $this->json('POST', $this->route("/account/signup"), $data);
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Phone number should be a minium of 10 characters');
    }

    public function testPhoneNumberMaximumCharacters()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_address' => $this->faker->email,
            'password' => 'secret',
            'phone_number' => '080'.rand(11111111111111, 99999999999999),
        ];

        $response = $this->json('POST', $this->route("/account/signup"), $data);
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Phone number should be a maximum of 15 characters');
    }

    public function testSignUpSuccessful()
    {
        Queue::fake();
        $response = $this->signupUser();
        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'status',
                'data',
                'message'
            ]
        );

        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->message, 'Registration successful. Kindly check your mail to activate your account');
        Queue::assertPushed(SendUserWelcomeMail::class, 1);
    }

    public function testRequiredLoginDetails()
    {
        $data = [
            'email_address' => '',
            'password' => 'secret',
        ];

        $response = $this->json('POST', $this->route("/account/signin"), $data);
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Email address is required');
    }

    public function testIncorrectLoginDetails()
    {
        $data = [
            'email_address' => $this->faker->email,
            'password' => 'secret',
        ];

        $response = $this->json('POST', $this->route("/account/signin"), $data);
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Incorrect login credentials. Try again.');
    }

    public function testLoginSuccessful()
    {
        $user = $this->signupUser();
        
        $data = [
            'email_address' => $user->getData()->data->email_address,
            'password' => 'secret',
        ];
        
        $response = $this->json('POST', $this->route("/account/signin"), $data);
        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->data->message, 'Login successful');
    }

    public function testGetUserProfile()
    {
        $response = $this->req()->JSON('GET', $this->route("/user/1"));
        
        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $response->assertOk()->assertJsonStructure(
            [
                'data' => [
                    'id', 'first_name', 'last_name', 'email_address', 'phone_number', 'state_id', 'city_id', 'state', 'city', 'business_name', 'business_slug', 'business_address', 'business_description', 'status', 'profile_picture', 'created_at', 'updated_at'
                ]
            ]
        );
    }

    public function testUpdatePersonalInformation()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_address' => $this->faker->email,
            'phone_number' => '080'.rand(111111111, 999999999),
            'state' => '1',
            'city' => '2',
        ];

        $response = $this->req()->json('PUT', $this->route("/user/profile/update/personal-information"), $data);

        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->data->message, 'Your profile was successfully updated.');
    }

    public function testUpdateBusinessInformation()
    {
        $data = [
            'business_name' => $this->faker->company,
            'business_slug' => $this->faker->word,
            'business_description' => $this->faker->text,
            'business_address' => $this->faker->address
        ];

        $response = $this->req()->json('PUT', $this->route("/user/profile/update/business-information"), $data);

        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->data->message, 'Your business profile was successfully updated.');
    }

    public function testNewPasswordDoesNotMatchTheConfirmationPassword()
    {
        $data = [
            'current_password' => 'secret',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secrett',
        ];

        $response = $this->req()->json('PUT', $this->route("/user/password/change"), $data);
        
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'The new password confirmation and new password must match.');
    }

    public function testPasswordStrength()
    {
        $data = [
            'current_password' => 'secret',
            'new_password' => 'secre',
            'new_password_confirmation' => 'secre',
        ];

        $response = $this->req()->json('PUT', $this->route("/user/password/change"), $data);
                
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'New password should be a minimum of 6 characters');
    }

    public function testInvalidCurrentPassword()
    {
        $data = [
            'current_password' => 'secre',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secret',
        ];

        $response = $this->req()->json('PUT', $this->route("/user/password/change"), $data);
       
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Incorrect current password.');
    }

    public function testCurrentPasswordMatchNewPassword()
    {
        $data = [
            'current_password' => 'secret',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secret',
        ];

        $response = $this->req()->json('PUT', $this->route("/user/password/change"), $data);
       
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Your new password should be different from your current password.');
    }

    public function testChangePasswordSuccessful()
    {
        $data = [
            'current_password' => 'secret',
            'new_password' => 'secret_password',
            'new_password_confirmation' => 'secret_password',
        ];

        $response = $this->req()->json('PUT', $this->route("/user/password/change"), $data);
        
        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->data->message, 'Your password was successfully updated.');
    }

    public function testUploadInvalidProfilePicture()
    {
        Storage::fake('local');
        $data = ['picture' => UploadedFile::fake()->image('picture.pdf')];

        $response = $this->req()->json('POST', $this->route("/user/picture/upload"), $data);
        
        $response->assertStatus(400);
        $this->assertEquals($response->getData()->status, 'error');
        $this->assertEquals($response->getData()->message, 'Only jpg and png files are allowed');
    }

    public function testCanUploadProfilePicture()
    {
        Storage::fake('local');
        $data = ['picture' => UploadedFile::fake()->image('picture.jpg')];

        $response = $this->req()->json('POST', $this->route("/user/picture/upload"), $data);
        
        $response->assertStatus(200);
        $this->assertEquals($response->getData()->status, 'success');
        $this->assertEquals($response->getData()->data->message, 'Your profile picture was successfully updated.');
    }
}
