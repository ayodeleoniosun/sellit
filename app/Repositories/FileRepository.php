<?php

namespace App\Repositories;

use App\Entities\Repositories\FileRepositoryInterface;
use App\Models\File;

class FileRepository implements FileRepositoryInterface
{
    private File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function create(array $data): File
    {
        return $this->file->create($data);
    }

    public function delete(int $fileId): void
    {
        $this->file->find($fileId)->delete();
    }
}
