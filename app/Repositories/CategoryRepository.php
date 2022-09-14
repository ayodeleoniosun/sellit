<?php

namespace App\Repositories;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\File;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CategoryRepository implements CategoryRepositoryInterface
{
    private Category $category;

    protected FileRepositoryInterface $fileRepo;

    public function __construct(Category $category, FileRepositoryInterface $fileRepo)
    {
        $this->category = $category;
        $this->fileRepo = $fileRepo;
    }

    public function getCategory(string $slug): ?Category
    {
        $category = $this->category->where('slug', $slug);

        if (!$category->first()) {
            return null;
        }

        return $category->with('subCategories', 'file')->first();
    }

    public function store(array $data): CategoryResource
    {
        if ($data['canUpdateIcon']) {
            $file = $this->fileRepo->create(['path' => $data['filename']]);
        }

        $data['file_id'] = $file->id ?? File::DEFAULT_ID;

        $this->category->create($data);

        return new CategoryResource($this->getCategory($data['slug']));
    }

    public function update(array $data, Category $category): CategoryResource
    {
        $formerIcon = $category->file;
        $canUpdateIcon = $data['canUpdateIcon'];

        if ($canUpdateIcon) {
            $file = $this->fileRepo->create(['path' => $data['filename']]);
            $data['file_id'] = $file->id;
        } else {
            $data['file_id'] = $category->file_id;
        }

        $category->update($data);

        $canDeleteIcon = $canUpdateIcon && $formerIcon->id !== File::DEFAULT_ID;

        if ($canDeleteIcon) {
            Storage::disk('s3')->delete($formerIcon->path);
            $this->fileRepo->delete($formerIcon->id);
        }

        return new CategoryResource($this->getCategory($category->slug));
    }
}
