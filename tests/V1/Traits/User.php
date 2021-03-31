<?php

namespace Tests\V1\Traits;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;

trait User
{
    use WithFaker;

    public function signupUser()
    {
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_address' => $this->faker->email,
            'password' => 'secret',
            'phone_number' => '080'.rand(111111111, 999999999),
        ];

        return $this->json('POST', $this->route("/account/signup"), $data);
    }
}
