<?php

namespace App\Services;

use App\Contracts\Repositories\Category\CategoryRepositoryInterface;
use App\Contracts\Repositories\Category\SubCategoryRepositoryInterface;
use App\Contracts\Services\CategoryServiceInterface;
use App\Exceptions\CustomException;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\SubCategory\SubCategoryCollection;
use App\Http\Resources\SubCategory\SubCategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        SubCategoryRepositoryInterface $subCategoryRepo,
    )
    {
        $this->categoryRepo = $categoryRepo;
        $this->subCategoryRepo = $subCategoryRepo;
    }

    public function index(Request $request): CategoryCollection
    {
        return new CategoryCollection($this->categoryRepo->index($request));
    }

    public function allSortOptions(Request $request): Collection
    {
        return $this->subCategoryRepo->allSortOptions($request);
    }

    public function sortOptionValues(Request $request, int $sortOptionId): Collection
    {
        return $this->subCategoryRepo->sortOptionValues($request, $sortOptionId);
    }

    public function subCategories(Request $request): SubCategoryCollection
    {
        return new SubCategoryCollection($this->subCategoryRepo->index($request));
    }

    /**
     * @param array $data
     * @return CategoryResource
     */
    public function store(array $data): CategoryResource
    {
        $name = $data['name'];
        $slug = Str::kebab($name);

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
            $category = $this->categoryRepo->store($data);
        } else {
            $category = $this->categoryRepo->update($data, $category);
        }

        return new CategoryResource($category);
    }

    public function addSubCategory(array $data): SubCategoryResource
    {
        $data['slug'] = Str::slug($data['name']);

        return new SubCategoryResource($this->subCategoryRepo->create($data));
    }

    public function updateSubCategory(array $data): SubCategoryResource
    {
        $slug = $data['slug'];
        $name = $data['name'];

        $subCategory = $this->subCategoryRepo->getSubCategory($slug);

        $data['slug'] = ($subCategory->name === $name) ? $slug : Str::slug($name);

        return new SubCategoryResource($this->subCategoryRepo->update($subCategory->id, $data));
    }

    /**
     * @throws CustomException
     */
    public function storeSortOptions(array $data, int $subCategoryId): int
    {
        $subCategory = $this->subCategoryRepo->find($subCategoryId);

        if (!$subCategory) {
            throw new CustomException('Sub category does not exist.');
        }

        return $this->subCategoryRepo->storeSortOptions($data['sort_options'], $subCategoryId);
    }

    /**
     * @throws CustomException
     */
    public function updateSortOptions(array $data, int $subCategoryId): array
    {
        $subCategory = $this->subCategoryRepo->find($subCategoryId);

        if (!$subCategory) {
            throw new CustomException('Sub category does not exist.');
        }

        return $this->subCategoryRepo->updateSortOptions($data['sort_options'], $subCategoryId);
    }
}
