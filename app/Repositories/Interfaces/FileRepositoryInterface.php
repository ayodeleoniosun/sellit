<?php

namespace App\Repositories\Interfaces;

use App\Models\File;

interface FileRepositoryInterface
{
    public function create(array $data): File;
}
