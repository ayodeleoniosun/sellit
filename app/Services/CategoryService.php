<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    protected CategoryRepositoryInterface $categoryRepo;

    /**
     * @param CategoryRepositoryInterface $categoryRepo
     */
    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * @param array $data
     * @return CategoryResource
     */
    public function store(array $data): CategoryResource
    {
        $name = $data['name'];
        $slug = Str::slug($name);

        $canUpdateIcon = !empty($data['icon']);
        return $this->createOrUpdate($data, $slug, $name,'store', $canUpdateIcon);
    }

    /**
     * @param array $data
     * @return CategoryResource
     */
    public function update(array $data): CategoryResource
    {
        $slug = $data['slug'];
        $name = $data['name'];

        $category = $this->categoryRepo->getCategory($slug);

        $slug = ($category->name === $name) ? $slug : Str::slug($name);
        $canUpdateIcon = !empty($data['icon']);

        return $this->createOrUpdate($data, $slug, $name, 'update', $canUpdateIcon, $category);
    }

    /**
     * @param array $data
     * @param mixed $slug
     * @param mixed $name
     * @return CategoryResource
     */
    private function createOrUpdate(array $data, mixed $slug, mixed $name, string $method, bool $canUpdateIcon = false, ?Category $category = null): CategoryResource
    {
        if ($canUpdateIcon) {
            $icon = $data['icon'];
            $extension = $icon->extension();

            $filename = $slug . strtolower(Str::random(8)) . '.' . $extension;
            Storage::disk('s3')->put($filename, file_get_contents($icon->getRealPath()));
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'filename' => $filename ?? '',
            'canUpdateIcon' => $canUpdateIcon
        ];

        if ($method === 'store') {
            return $this->categoryRepo->store($data);
        }

        return $this->categoryRepo->update($data, $category);
    }
}
