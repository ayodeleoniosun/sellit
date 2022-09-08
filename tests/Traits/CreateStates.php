<?php

namespace Tests\Traits;

use App\Models\State;

trait CreateStates
{
    protected function createState()
    {
        return State::factory()->create();
    }
}
