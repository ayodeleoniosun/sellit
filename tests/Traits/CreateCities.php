<?php

namespace Tests\Traits;

use App\Models\City;

trait CreateCities
{
    protected function createCity()
    {
        return City::factory()->create();
    }
}
