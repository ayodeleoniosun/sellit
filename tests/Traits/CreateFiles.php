<?php

namespace Tests\Traits;

use App\Models\File;

trait CreateFiles
{
    protected function createFile()
    {
        return File::factory()->create();
    }
}
