<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name'     => $this->faker->name(),
            'last_name'      => $this->faker->name(),
            'slug'           => Str::slug($this->faker->name()) . '-' . strtolower(Str::random(8)),
            'email_address'  => $this->faker->unique()->safeEmail(),
            'phone_number'   => Str::random(11),
            'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function verified()
    {
        return $this->state(function () {
            return ['email_verified_at' => now()];
        });
    }
}
