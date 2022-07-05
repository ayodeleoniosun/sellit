<?php

namespace Tests;

use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Model;

trait CreatePasswordResets
{
    protected function createPasswordReset($state = null): PasswordReset|Model
    {
        return is_null($state) ? PasswordReset::factory()->create()
            : PasswordReset::factory()->{$state}()->create();
    }
}
