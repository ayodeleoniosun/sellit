<?php

namespace Tests\Unit\Repository;

use App\Models\File;
use Illuminate\Support\Str;

beforeEach(function () {
    $this->file = new File();
});

test('create new file', function () {
    $file = $this->file->create(['path' => Str::random(11) . '.jpg']);
    $this->assertInstanceOf(File::class, $file);
});
