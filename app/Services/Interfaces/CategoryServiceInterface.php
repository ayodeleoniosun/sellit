<?php

namespace App\Services\Interfaces;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubCategoryCollection;
use App\Http\Resources\SubCategoryResource;
use Illuminate\Http\Request;

interface CategoryServiceInterface
{
    public function index(Request $request): CategoryCollection;

    public function subCategories(Request $request): SubCategoryCollection;

    public function store(array $data): CategoryResource;

    public function update(array $data): CategoryResource;

    public function addSubCategory(array $data): SubCategoryResource;

    public function updateSubCategory(array $data): SubCategoryResource;
}
