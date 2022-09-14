<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SubCategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    protected CategoryRepositoryInterface $categoryRepo;

    protected SubCategoryRepositoryInterface $subCategoryRepo;

    /**
     * @param CategoryRepositoryInterface $categoryRepo
     * @param SubCategoryRepositoryInterface $subCategoryRepo
     */
    public function __construct(CategoryRepositoryInterface $categoryRepo, SubCategoryRepositoryInterface $subCategoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->subCategoryRepo = $subCategoryRepo;
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
        return $this->createOrUpdateCategory($data, $slug, $name,'store', $canUpdateIcon);
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

        return $this->createOrUpdateCategory($data, $slug, $name, 'update', $canUpdateIcon, $category);
    }

    /**
     * @param array $data
     * @param mixed $slug
     * @param mixed $name
     * @return CategoryResource
     */
    private function createOrUpdateCategory(array $data, mixed $slug, mixed $name, string $method, bool $canUpdateIcon = false, ?Category $category = null): CategoryResource
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

    public function addSubCategory(array $data): SubCategoryResource
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->subCategoryRepo->store($data);
    }

    public function updateSubCategory(array $data): SubCategoryResource
    {
        $slug = $data['slug'];
        $name = $data['name'];

        $subCategory = $this->subCategoryRepo->getSubCategory($slug);

        $data['slug'] = ($subCategory->name === $name) ? $slug : Str::slug($name);

        return $this->subCategoryRepo->update($data, $subCategory);
    }
}
