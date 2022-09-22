<?php

namespace App\Entities\Repositories;

use App\Models\File;

interface FileRepositoryInterface
{
    public function create(array $data): File;

    public function delete(int $fileId): void;
}
