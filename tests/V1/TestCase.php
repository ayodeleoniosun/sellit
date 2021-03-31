<?php

namespace Tests\V1;

use App\Modules\Api\V1\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;
use Tests\V1\Traits\User as TraitsUser;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, TraitsUser;
    public $baseURL;
    protected $user;

    public function setup() : void
    {
        parent::setUp();
        $this->baseURL = sprintf('http://%s/api/v1', env('APP_DOMAIN'));
        $this->faker = \Faker\Factory::create();
    }

    public function route($route) : string
    {
        return sprintf('%s%s', $this->baseURL, $route);
    }

    public function req()
    {
        $this->user = $this->signupUser();
        $token = $this->user->getData()->data->bearer_token;
        
        return $this->withHeaders(
            [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json'
            ]
        );
    }
}
