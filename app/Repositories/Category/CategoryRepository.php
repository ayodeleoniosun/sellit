<?php

namespace App\Repositories\Category;

use App\Contracts\Repositories\Category\CategoryRepositoryInterface;
use App\Contracts\Repositories\FileRepositoryInterface;
use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function index(Request $request): LengthAwarePaginator
    {
        return Category::with('subCategories')->paginate(10);
    }

    public function getCategory(string $slug): ?Category
    {
        $category = $this->category->where('slug', $slug);

        if (!$category->first()) {
            return null;
        }

        return $category->with('subCategories', 'file')->first();
    }

    public function store(array $data): Category
    {
        if ($data['canUpdateIcon']) {
            $file = $this->fileRepo->create(['path' => $data['filename']]);
        }

        $data['file_id'] = $file->id ?? File::DEFAULT_ID;

        $this->category->create($data);

        return $this->getCategory($data['slug']);
    }

    public function update(array $data, Category $category): Category
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

        return $this->getCategory($category->slug);
    }
}
