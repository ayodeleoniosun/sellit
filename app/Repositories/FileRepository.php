<?php

namespace App\Repositories;

use App\Contracts\Repositories\File\FileRepositoryInterface;
use App\Models\File;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    private File $file;

    public function __construct(File $file)
    {
        parent::__construct($file);
        $this->file = $file;
    }
}
