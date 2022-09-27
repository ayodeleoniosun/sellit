<?php

namespace App\Contracts\Repositories\File;

interface FileRepositoryInterface
{
    public function delete(int $fileId): void;
}
