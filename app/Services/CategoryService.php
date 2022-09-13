<?php

namespace App\Services;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    protected CategoryRepositoryInterface $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function store(Request $request): CategoryResource
    {
        $icon = $request->icon;
        $extension = $icon->extension();
        $slug = Str::slug($request->name);
        $filename = $slug.strtolower(Str::random(8)).'.'.$extension;

        Storage::disk('s3')->put($filename, file_get_contents($icon->getRealPath()));

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'filename' => $filename
        ];

        return $this->categoryRepo->store($data);
    }
}
