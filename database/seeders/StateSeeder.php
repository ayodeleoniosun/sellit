<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            'Abia',
            'Adamawa',
            'Akwa Ibom',
            'Anambra',
            'Bauchi',
            'Bayelsa',
            'Benue',
            'Borno',
            'Cross River',
            'Delta',
            'Ebonyi',
            'Edo',
            'Ekiti',
            'Enugu',
            'FCT - Abuja',
            'Gombe',
            'Imo',
            'Jigawa',
            'Kaduna',
            'Kano',
            'Katsina',
            'Kebbi',
            'Kogi',
            'Kwara',
            'Lagos',
            'Nasarawa',
            'Niger',
            'Ogun',
            'Ondo',
            'Osun',
            'Oyo',
            'Plateau',
            'Rivers',
            'Sokoto',
            'Taraba',
            'Yobe',
            'Zamfara',
        ];

        foreach ($states as $state) {
            State::create(['name' => strtolower($state)]);
        }
    }
}
