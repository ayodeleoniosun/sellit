<?php

use App\ApiUtility;
use App\Models\User;

$factory->define(
    User::class,
    function (Faker\Generator $faker) {
        return [
            'email' => $faker->email,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'phone' => '080'.rand(111111111, 999999999),
            'password' => bcrypt('secret'),
            'bearer_token' => ApiUtility::generate_bearer_token(),
            'token_expires_at' => ApiUtility::next_one_month(),
        ];
    }
);
