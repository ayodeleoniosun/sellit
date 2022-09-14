<?php

namespace App\Services\Interfaces;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubCategoryResource;

interface CategoryServiceInterface
{
    public function store(array $data): CategoryResource;

    public function update(array $data): CategoryResource;

    public function addSubCategory(array $data): SubCategoryResource;

    public function updateSubCategory(array $data): SubCategoryResource;
}
