<?php

namespace App\Repositories;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    private Category $category;

    protected FileRepositoryInterface $fileRepo;

    public function __construct(Category $category, FileRepositoryInterface $fileRepo)
    {
        $this->category = $category;
        $this->fileRepo = $fileRepo;
    }

    public function getCategory(string $slug): ?CategoryResource
    {
        $category = $this->category->where('slug', $slug);

        if ($category->first()) {
            $record = $category->with('subCategories', 'file')->first();
            return new CategoryResource($record);
        }

        return null;
    }

    public function store(array $data): CategoryResource
    {
        $file = $this->fileRepo->create(['path' => $data['filename']]);

        $this->category->name = $data['name'];
        $this->category->slug = $data['slug'];
        $this->category->file_id = $file->id;
        $this->category->save();

        $this->category->fresh();

        return $this->getCategory($this->category->slug);
    }
}
